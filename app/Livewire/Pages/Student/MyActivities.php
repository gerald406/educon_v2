<?php

namespace App\Livewire\Pages\Student;

use App\Models\AcademicActivity;
use App\Models\AcademicPeriod;
use App\Models\ActivitySubmission;
use App\Models\Enrollment;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class MyActivities extends Component
{
    use WithFileUploads;

    // --- DATOS DEL CONTEXTO ---
    public ?AcademicPeriod $activePeriod = null;
    public ?Student $student = null;
    public ?Enrollment $currentEnrollment = null;
    
    // --- LISTAS ---
    public Collection $enrolledCourses; // Los cursos (registrations) del estudiante
    public Collection $activities; // Las actividades del curso seleccionado
    public Collection $submissions; // Las entregas del estudiante

    // --- ESTADO ---
    public $selectedRegistrationId = null;
    public $isModalOpen = false;
    public ?AcademicActivity $activityToSubmit = null;

    // --- FORMULARIO DE ENTREGA ---
    public $fileUpload;
    public $studentComments = '';

    public function mount()
    {
        $this->student = Auth::user()->student;
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $this->enrolledCourses = collect();
        $this->activities = collect();
        $this->submissions = collect();

        if ($this->student && $this->activePeriod) {
            // Buscar la matrícula del estudiante en este periodo
            $this->currentEnrollment = Enrollment::where('student_id', $this->student->id)
                ->where('academic_period_id', $this->activePeriod->id)
                ->first();

            if ($this->currentEnrollment) {
                // Cargar los cursos (registrations) en los que está inscrito
                $this->enrolledCourses = $this->currentEnrollment->registrations()
                    ->with('teacherAssignment.didacticUnit')
                    ->get();
            }
        }
    }
    
    /**
     * Hook: Se dispara al seleccionar un curso de la lista.
     */
    public function updatedSelectedRegistrationId($regId)
    {
        if (empty($regId)) {
            $this->activities = collect();
            $this->submissions = collect();
            return;
        }

        $registration = $this->enrolledCourses->find($regId);
        
        // 1. Cargar las actividades de esa sección (asignación)
        $this->activities = AcademicActivity::where('teacher_assignment_id', $registration->teacher_assignment_id)
            ->orderBy('due_date', 'desc')
            ->get();
            
        // 2. Cargar las entregas (submissions) que este estudiante ya hizo
        $this->submissions = ActivitySubmission::where('registration_id', $regId)
            ->whereIn('academic_activity_id', $this->activities->pluck('id'))
            ->get()
            ->keyBy('academic_activity_id'); // Indexar por ID de actividad
    }
    
    /**
     * Abre el modal para entregar una actividad.
     */
    public function openSubmitModal(AcademicActivity $activity)
    {
        $this->activityToSubmit = $activity;
        $this->fileUpload = null;
        
        // Cargar datos de la entrega si ya existe
        $existingSubmission = $this->submissions->get($activity->id);
        $this->studentComments = $existingSubmission?->student_comments ?? '';
        
        $this->isModalOpen = true;
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->activityToSubmit = null;
    }
    
    /**
     * Guarda la entrega del estudiante.
     */
    public function saveSubmission()
    {
        $this->validate([
            'fileUpload' => 'required|file|mimes:pdf,doc,docx,zip,rar,jpg,png|max:10240', // 10MB
            'studentComments' => 'nullable|string|max:500',
        ]);
        
        $registrationId = $this->selectedRegistrationId;
        $activityId = $this->activityToSubmit->id;

        try {
            $submission = ActivitySubmission::where('academic_activity_id', $activityId)
                            ->where('registration_id', $registrationId)
                            ->first();

            // Borrar archivo antiguo si existe
            if ($submission && $submission->submission_file_url) {
                Storage::disk('public')->delete($submission->submission_file_url);
            }
            
            // Subir nuevo archivo
            $path = $this->fileUpload->store('submissions', 'public');
            
            // Crear o Actualizar el registro
            ActivitySubmission::updateOrCreate(
                [
                    'academic_activity_id' => $activityId,
                    'registration_id' => $registrationId,
                ],
                [
                    'submission_date' => now(),
                    'submission_file_url' => $path,
                    'student_comments' => $this->studentComments,
                    'status' => 'submitted',
                    'grade' => null, // Resetea la nota si es una re-entrega
                    'review_date' => null,
                    'reviewed_by_user_id' => null,
                    'teacher_comments' => null,
                ]
            );
            
            $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Entregado!', 'text' => 'Tu actividad ha sido entregada.']);
            $this->closeModal();
            $this->updatedSelectedRegistrationId($registrationId); // Recargar

        } catch (\Exception $e) {
             $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.pages.student.my-activities');
    }
}