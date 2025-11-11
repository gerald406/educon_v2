<?php

namespace App\Livewire\Pages\Teacher;

use App\Models\AcademicActivity;
use App\Models\AcademicPeriod;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ActivityManager extends Component
{
    use WithPagination, WithFileUploads;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $title = '';
    public $description = '';
    public $activity_type = 'practice';
    public $assigned_date = '';
    public $due_date = '';
    public $weight = 0;
    public $activity_file_url = '';
    public $fileUpload; // Archivo temporal

    // --- PROPIEDADES DE ESTADO ---
    public ?AcademicPeriod $activePeriod = null;
    public ?Teacher $currentTeacher = null;
    public $selectedAssignmentId = '';
    public ?AcademicActivity $editingActivity = null;
    public $isModalOpen = false;

    // --- COLECCIONES ---
    public Collection $assignments; // Secciones del docente

    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $this->currentTeacher = Auth::user()->teacher;
        $this->loadAssignments();

        $this->assigned_date = now()->format('Y-m-d\TH:i');
        $this->due_date = now()->addWeek()->format('Y-m-d\TH:i');
    }

    public function loadAssignments()
    {
        if ($this->currentTeacher && $this->activePeriod) {
            $this->assignments = TeacherAssignment::where('teacher_id', $this->currentTeacher->id)
                ->where('academic_period_id', $this->activePeriod->id)
                ->with('didacticUnit', 'shift')
                ->get()
                ->mapWithKeys(fn($a) => 
                    [$a->id => "{$a->didacticUnit->name} (Sec. {$a->section})"]
                );
        } else {
            $this->assignments = collect();
        }
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'activity_type' => 'required|in:practice,project,research,presentation,exam,workshop,laboratory',
            'assigned_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:assigned_date',
            'weight' => 'required|numeric|min:0|max:100',
            'fileUpload' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,jpg,png|max:10240', // 10MB Max
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(AcademicActivity $activity)
    {
        $this->editingActivity = $activity;
        $this->fill($activity);
        $this->activity_file_url = $activity->activity_file_url; // Almacenar la URL existente
        $this->assigned_date = $activity->assigned_date->format('Y-m-d\TH:i');
        $this->due_date = $activity->due_date->format('Y-m-d\TH:i');
        $this->fileUpload = null;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset('title', 'description', 'activity_type', 'assigned_date', 'due_date', 'weight', 'activity_file_url', 'fileUpload', 'editingActivity');
        $this->mount(); // Recarga fechas por defecto
        $this->resetValidation();
    }

    public function save()
    {
        if (empty($this->selectedAssignmentId)) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Debe seleccionar una sección primero.']);
            return;
        }

        $data = $this->validate();
        $data['teacher_assignment_id'] = $this->selectedAssignmentId;

        try {
            // Manejo del archivo
            if ($this->fileUpload) {
                // Borrar archivo anterior si se está editando y existe uno
                if ($this->editingActivity && $this->editingActivity->activity_file_url) {
                    Storage::disk('public')->delete($this->editingActivity->activity_file_url);
                }
                $data['activity_file_url'] = $this->fileUpload->store('activities', 'public');
            } else {
                // Mantener el archivo anterior si no se sube uno nuevo
                $data['activity_file_url'] = $this->editingActivity?->activity_file_url;
            }

            AcademicActivity::updateOrCreate(
                ['id' => $this->editingActivity?->id], // Clave de búsqueda
                $data // Datos para actualizar o crear
            );

            $this->closeModal();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Hecho!',
                'text' => 'Actividad guardada correctamente.',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Actividad?',
            'text' => 'Esto eliminará la actividad y todas las entregas de los estudiantes.',
            'onConfirmed' => 'deleteActivity'
        ]);
    }

    #[On('deleteActivity')]
    public function deleteActivity(int $id)
    {
        try {
            $activity = AcademicActivity::findOrFail($id);
            // (Opcional: borrar archivo de storage)
            if ($activity->activity_file_url) {
                Storage::disk('public')->delete($activity->activity_file_url);
            }
            $activity->delete(); // 'onDelete('cascade')' borrará las entregas
            $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Eliminado!']);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo eliminar la actividad.']);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $activities = collect();
        if ($this->selectedAssignmentId) {
            $activities = AcademicActivity::where('teacher_assignment_id', $this->selectedAssignmentId)
                ->orderBy('due_date', 'desc')
                ->paginate(5);
        }

        return view('livewire.pages.teacher.activity-manager', [
            'activities' => $activities,
        ]);
    }
}