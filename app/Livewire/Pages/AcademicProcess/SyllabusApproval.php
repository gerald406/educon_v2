<?php

namespace App\Livewire\Pages\AcademicProcess;

use App\Models\AcademicPeriod;
use App\Models\CareerCoordinator;
use App\Models\Syllabus;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SyllabusApproval extends Component
{
    use WithPagination;

    public ?AcademicPeriod $activePeriod = null;
    public $search = '';

    // --- Modal de Observación ---
    public $isObserveModalOpen  = false;
    public ?Syllabus $syllabusToObserve = null;
    public $observationNotes    = '';

    // --- Carrera del coordinador autenticado ---
    public ?int $coordinatorCareerId = null;

    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();

        // Obtener la carrera asignada al coordinador autenticado
        $coordinator = CareerCoordinator::where('user_id', Auth::id())
            ->where('is_active', true)
            ->first();

        $this->coordinatorCareerId = $coordinator?->career_id;
    }

    // ============================================
    // APROBAR
    // ============================================

    public function approve(Syllabus $syllabus)
    {
        // Verificar que el sílabo pertenece a la carrera del coordinador
        if (!$this->syllabusBelongsToCoordinator($syllabus)) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Acceso Denegado',
                'text'  => 'Este sílabo no pertenece a tu carrera asignada.',
            ]);
            return;
        }

        // Verificar que está en estado correcto
        if ($syllabus->status !== 'submitted') {
            $this->dispatch('swal', [
                'icon'  => 'warning',
                'title' => 'Estado Inválido',
                'text'  => 'Solo puedes aprobar sílabos en estado "Enviado".',
            ]);
            return;
        }

        try {
            $syllabus->update([
                'status'            => 'approved',
                'observation_notes' => null,
                'approved_at'       => now(),
                'approved_by'       => Auth::id(),
            ]);

            $this->dispatch('swal', [
                'icon'  => 'success',
                'title' => '¡Aprobado!',
                'text'  => 'El sílabo ha sido aprobado correctamente.',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $e->getMessage(),
            ]);
        }
    }

    // ============================================
    // OBSERVAR
    // ============================================

    public function openObserveModal(Syllabus $syllabus)
    {
        // Verificar pertenencia antes de abrir el modal
        if (!$this->syllabusBelongsToCoordinator($syllabus)) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Acceso Denegado',
                'text'  => 'Este sílabo no pertenece a tu carrera asignada.',
            ]);
            return;
        }

        $this->syllabusToObserve = $syllabus->load(
            'teacherAssignment.didacticUnit'
        );
        $this->observationNotes  = $syllabus->observation_notes ?? '';
        $this->isObserveModalOpen = true;
    }

    public function saveObservation()
    {
        $this->validate([
            'observationNotes' => 'required|string|min:10',
        ], [
            'observationNotes.required' => 'Debe ingresar un motivo para la observación.',
            'observationNotes.min'      => 'La observación debe tener al menos 10 caracteres.',
        ]);

        // Doble verificación de pertenencia al guardar
        if (!$this->syllabusToObserve || !$this->syllabusBelongsToCoordinator($this->syllabusToObserve)) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Acceso Denegado',
                'text'  => 'No tienes permiso para observar este sílabo.',
            ]);
            $this->closeModal();
            return;
        }

        try {
            $this->syllabusToObserve->update([
                'status'            => 'observed',
                'observation_notes' => $this->observationNotes,
                'approved_at'       => null,
                'approved_by'       => null,
            ]);

            $this->dispatch('swal', [
                'icon'  => 'info',
                'title' => '¡Observado!',
                'text'  => 'El sílabo ha sido devuelto al docente con observaciones.',
            ]);

            $this->closeModal();
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => $e->getMessage(),
            ]);
        }
    }

    public function closeModal()
    {
        $this->isObserveModalOpen  = false;
        $this->syllabusToObserve   = null;
        $this->observationNotes    = '';
        $this->resetErrorBag();
    }

    // ============================================
    // HELPER — verificar pertenencia
    // ============================================

    /**
     * Verifica que el sílabo pertenece a la carrera
     * asignada al coordinador autenticado.
     * El Administrador bypasea este filtro.
     */
    protected function syllabusBelongsToCoordinator(Syllabus $syllabus): bool
    {
        // El Administrador puede aprobar cualquier sílabo
        if (Auth::user()->hasRole('Administrador')) {
            return true;
        }

        // Si el coordinador no tiene carrera asignada, denegar
        if (!$this->coordinatorCareerId) {
            return false;
        }

        // Verificar cadena:
        // Syllabus → TeacherAssignment → DidacticUnit → Module → StudyPlan → Career
        return Syllabus::where('syllabi.id', $syllabus->id)
            ->whereHas('teacherAssignment.didacticUnit.module.studyPlan', function ($q) {
                $q->where('career_id', $this->coordinatorCareerId);
            })
            ->exists();
    }

    // ============================================
    // RENDER
    // ============================================

    public function render()
    {
        $syllabi = collect();

        if ($this->activePeriod) {

            $query = Syllabus::pendingApproval() // scope: where status = 'submitted'
                ->whereHas('teacherAssignment', function ($q) {
                    $q->where('academic_period_id', $this->activePeriod->id);
                })
                ->with([
                'teacherAssignment.teacher.user',
                'teacherAssignment.didacticUnit.module.studyPlan.career',
                'teacherAssignment.shift',
                ]);

            // Filtrar por carrera del coordinador (salvo Administrador)
            if (!Auth::user()->hasRole('Administrador') && $this->coordinatorCareerId) {
                $query->whereHas(
                    'teacherAssignment.didacticUnit.module.studyPlan',
                    fn($q) => $q->where('career_id', $this->coordinatorCareerId)
                );
            }

            // Búsqueda por nombre de curso o docente
            if ($this->search) {
                $query->where(function ($q) {
                    $q->whereHas(
                        'teacherAssignment.didacticUnit',
                        fn($sq) => $sq->where('name', 'like', '%' . $this->search . '%')
                    )->orWhereHas(
                        'teacherAssignment.teacher.user',
                        fn($sq) => $sq->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('lastname', 'like', '%' . $this->search . '%')
                    );
                });
            }

            $syllabi = $query->orderBy('submitted_at', 'asc')->paginate(10);
        }

        return view('livewire.pages.academic-process.syllabus-approval', [
            'syllabi'             => $syllabi,
            'coordinatorCareerId' => $this->coordinatorCareerId,
        ]);
    }
}
