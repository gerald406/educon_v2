<?php

namespace App\Livewire\Pages\AcademicProcess;

use App\Models\AcademicPeriod;
use App\Models\Syllabus;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On; // <-- [AÑADIR]
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SyllabusApproval extends Component
{
    use WithPagination;

    public ?AcademicPeriod $activePeriod = null;
    public $search = '';

    // --- [NUEVO] Propiedades del Modal de Observación ---
    public $isObserveModalOpen = false;
    public ?Syllabus $syllabusToObserve = null;
    public $observationNotes = '';
    // ---

    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
    }

    /**
     * Acción: Aprobar un sílabo.
     */
    public function approve(Syllabus $syllabus)
    {
        try {
            $syllabus->update([
                'status' => 'approved',
                'observation_notes' => null, // Limpiar observaciones
                'approval_date' => now(),
                'approved_by_user_id' => Auth::id(),
            ]);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Aprobado!',
                'text' => 'El sílabo ha sido aprobado.',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    /**
     * [NUEVO] Abre el modal para escribir la observación.
     */
    public function openObserveModal(Syllabus $syllabus)
    {
        $this->syllabusToObserve = $syllabus;
        $this->observationNotes = $syllabus->observation_notes ?? '';
        $this->isObserveModalOpen = true;
    }

    /**
     * [NUEVO] Cierra el modal.
     */
    public function closeModal()
    {
        $this->isObserveModalOpen = false;
        $this->syllabusToObserve = null;
        $this->observationNotes = '';
        $this->resetErrorBag();
    }

    /**
     * [NUEVO] Guarda la observación.
     */
    public function saveObservation()
    {
        $this->validate([
            'observationNotes' => 'required|string|min:10',
        ], [
            'observationNotes.required' => 'Debe ingresar un motivo para la observación.',
            'observationNotes.min' => 'La observación debe tener al menos 10 caracteres.',
        ]);

        try {
            $this->syllabusToObserve->update([
                'status' => 'observed',
                'observation_notes' => $this->observationNotes,
                'approval_date' => null,
                'approved_by_user_id' => null,
            ]);

            $this->dispatch('swal', [
                'icon' => 'info',
                'title' => '¡Observado!',
                'text' => 'El sílabo ha sido marcado como observado.',
            ]);
            $this->closeModal();

        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    // ... (render() sin cambios) ...
    public function render()
    {
        $syllabi = collect(); 

        if ($this->activePeriod) {
            $query = Syllabus::query()
                ->where('status', 'pending_approval') 
                ->whereHas('teacherAssignment', function ($q) {
                    $q->where('academic_period_id', $this->activePeriod->id);
                })
                ->with([
                    'teacherAssignment.didacticUnit', 
                    'teacherAssignment.teacher.user' 
                ]);

            if ($this->search) {
                $query->where(function ($q) {
                    $q->whereHas('teacherAssignment.didacticUnit', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'))
                        ->orWhereHas('teacherAssignment.teacher.user', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'));
                });
            }
            
            $syllabi = $query->paginate(10);
        }

        return view('livewire.pages.academic-process.syllabus-approval', [
            'syllabi' => $syllabi,
        ]);
    }
}