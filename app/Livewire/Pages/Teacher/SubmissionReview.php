<?php

namespace App\Livewire\Pages\Teacher;

use App\Models\AcademicActivity;
use App\Models\AcademicPeriod;
use App\Models\ActivitySubmission;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class SubmissionReview extends Component
{
    use WithPagination;

    // --- CONTEXTO ---
    public ?AcademicPeriod $activePeriod = null;
    public ?Teacher $currentTeacher = null;
    public Collection $assignments; // Secciones del docente

    // --- FILTROS ---
    public $selectedAssignmentId = '';
    public $selectedActivityId = '';

    // --- DATOS ---
    public Collection $activities; // Actividades de la sección
    public $submissions; // Entregas de la actividad (paginadas)

    // --- MODAL DE CALIFICACIÓN ---
    public $isModalOpen = false;
    public ?ActivitySubmission $editingSubmission = null;
    public $grade = '';
    public $teacherComments = '';

    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $this->currentTeacher = Auth::user()->teacher;
        $this->loadAssignments();
        $this->activities = collect();
        $this->submissions = collect();
    }

    public function loadAssignments()
    {
        if ($this->currentTeacher && $this->activePeriod) {
            $this->assignments = TeacherAssignment::where('teacher_id', $this->currentTeacher->id)
                ->where('academic_period_id', $this->activePeriod->id)
                ->with('didacticUnit')
                ->get()
                ->mapWithKeys(fn($a) => 
                    [$a->id => "{$a->didacticUnit->name} (Sec. {$a->section})"]
                );
        } else {
            $this->assignments = collect();
        }
    }

    /**
     * Hook: Cargar actividades cuando se selecciona una sección.
     */
    public function updatedSelectedAssignmentId($assignmentId)
    {
        $this->selectedActivityId = '';
        $this->activities = AcademicActivity::where('teacher_assignment_id', $assignmentId)
                            ->orderBy('due_date', 'desc')
                            ->pluck('title', 'id');
        $this->submissions = collect();
    }
    
    /**
     * Hook: Cargar entregas cuando se selecciona una actividad.
     */
    public function updatedSelectedActivityId()
    {
        $this->resetPage(); // Reinicia la paginación
    }

    // --- ACCIONES DEL MODAL ---
    
    public function openGradeModal(ActivitySubmission $submission)
    {
        $this->editingSubmission = $submission;
        $this->grade = $submission->grade ?? '';
        $this->teacherComments = $submission->teacher_comments ?? '';
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset('editingSubmission', 'grade', 'teacherComments');
        $this->resetErrorBag();
    }

    public function saveGrade()
    {
        $this->validate([
            'grade' => 'required|numeric|min:0|max:20',
            'teacherComments' => 'nullable|string|max:1000',
        ]);

        if ($this->editingSubmission) {
            $this->editingSubmission->update([
                'grade' => $this->grade,
                'teacher_comments' => $this->teacherComments,
                'status' => 'reviewed',
                'review_date' => now(),
                'reviewed_by_user_id' => Auth::id(),
            ]);
            
            $this->closeModal();
            $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Calificado!', 'text' => 'La entrega ha sido calificada.']);
        }
    }

    // --- RENDER ---
    public function render()
    {
        if ($this->selectedActivityId) {
            // Cargar estudiantes inscritos en la sección
            $assignment = TeacherAssignment::find($this->selectedAssignmentId);
            
            // Obtener las entregas (submissions) de esa actividad
            // y hacer un join con los estudiantes (registrations)
            $this->submissions = ActivitySubmission::with('registration.student.user')
                ->where('academic_activity_id', $this->selectedActivityId)
                // Opcional: Cargar también los estudiantes que NO entregaron
                // (Esto es más complejo, lo omitimos por ahora)
                ->paginate(15);
        }

        return view('livewire.pages.teacher.submission-review', [
            'submissionsData' => $this->submissions,
        ]);
    }
}