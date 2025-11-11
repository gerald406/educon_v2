<?php

namespace App\Livewire\Pages\Admission;

use App\Models\Applicant;
use App\Models\Career;
use App\Models\Student;
use App\Models\StudyPlan;
use App\Models\User;
use App\Models\AcademicPeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')] // <-- [LA CORRECCIÓN AL ERROR]
class ApplicantManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $user = ['name' => '', 'email' => ''];
    public $applicant = ['code' => '', 'admission_type' => 'regular', 'exam_score' => null];
    public $selectedCareerId = '';
    public $selectedStudyPlanId = '';
    
    // --- PROPIEDADES DE ESTADO ---
    public ?User $editingUser = null;
    public ?Applicant $editingApplicant = null;
    public $isModalOpen = false;
    public $search = '';

    // --- DATOS PARA DROPDOWNS ---
    public Collection $careers;
    public Collection $availableStudyPlans;

    public function mount()
    {
        $this->careers = Career::where('status', 'active')->pluck('name', 'id');
        $this->availableStudyPlans = collect();

        if ($this->careers->count() > 0) {
            $this->selectedCareerId = $this->careers->keys()->first();
            $this->updateAvailableStudyPlans();
        }
    }

    public function updatedSelectedCareerId($value)
    {
        $this->updateAvailableStudyPlans();
        $this->selectedStudyPlanId = $this->availableStudyPlans->keys()->first() ?? '';
    }

    public function updateAvailableStudyPlans()
    {
        $this->availableStudyPlans = StudyPlan::where('career_id', $this->selectedCareerId)
            ->where('status', 'active')
            ->pluck('name', 'id');
    }

    protected function rules()
    {
        return [
            'user.name' => 'required|string|max:255',
            'user.email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingUser?->id)],
            'selectedCareerId' => 'required|exists:careers,id',
            'selectedStudyPlanId' => 'required|exists:study_plans,id',
            'applicant.code' => ['required', 'string', 'max:20', Rule::unique('applicants', 'code')->ignore($this->editingApplicant?->id)],
            'applicant.admission_type' => 'required|in:regular,extraordinary,external_transfer,internal_transfer',
            'applicant.exam_score' => 'nullable|numeric|min:0|max:20',
        ];
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(User $user)
    {
        $this->editingUser = $user;
        $this->editingApplicant = $user->applicant;

        $this->user['name'] = $user->name;
        $this->user['email'] = $user->email;

        $this->applicant = $user->applicant->only('code', 'admission_type', 'exam_score');
        $this->selectedCareerId = $user->applicant->career_id;
        $this->updateAvailableStudyPlans();
        $this->selectedStudyPlanId = $user->applicant->study_plan_id;
        
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset('user', 'applicant', 'editingUser', 'editingApplicant');
        $this->resetValidation();
        if ($this->careers->count() > 0) {
            $this->selectedCareerId = $this->careers->keys()->first();
            $this->updateAvailableStudyPlans();
            $this->selectedStudyPlanId = $this->availableStudyPlans->keys()->first() ?? '';
        }
    }

    public function save()
    {
        $data = $this->validate();

        try {
            DB::transaction(function () use ($data) {
                $user = $this->editingUser ?? new User();
                $user->fill($data['user']);
                if (!$this->editingUser) {
                    $user->password = Hash::make(substr(str_replace(' ', '', $data['user']['name']), 0, 8)); // Contraseña por defecto
                }
                $user->save();

                // Asignar rol (usamos 'Estudiante' como definimos en el factory)
                $user->assignRole('Estudiante');
                
                $applicantData = $data['applicant'];
                $applicantData['user_id'] = $user->id;
                $applicantData['career_id'] = $this->selectedCareerId;
                $applicantData['study_plan_id'] = $this->selectedStudyPlanId;
                // Asumimos que el periodo de admisión es el activo
                $applicantData['academic_period_id'] = AcademicPeriod::where('status', 'active')->first()->id;

                $applicant = $this->editingApplicant ?? new Applicant();
                $applicant->fill($applicantData);
                $applicant->save();
            });

            $this->closeModal();
            $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Hecho!', 'text' => 'Postulante guardado correctamente.']);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    /**
     * Muestra la confirmación de SweetAlert antes de aprobar.
     */
    public function confirmApprove(int $userId)
    {
        // [TEXTO DE BOTÓN CORREGIDO]
        $confirmText = 'Sí, ¡aprobar!';
        
        $applicant = Applicant::where('user_id', $userId)->first();
        if (empty($applicant->exam_score) || $applicant->exam_score < 13) { // Asumimos 13 como nota mínima
            $this->dispatch('swal:confirm', [
                'id' => $userId,
                'title' => '¿Aprobar Postulante?',
                'text' => 'El postulante no tiene una nota de examen válida o está desaprobado. ¿Desea aprobarlo de todas formas?',
                'icon' => 'warning',
                'onConfirmed' => 'approveApplicant',
                'confirmButtonText' => $confirmText // <-- [LÍNEA AÑADIDA]
            ]);
        } else {
            $this->dispatch('swal:confirm', [
                'id' => $userId,
                'title' => '¿Aprobar Postulante?',
                'text' => 'Esta acción creará un registro de estudiante para este usuario.',
                'onConfirmed' => 'approveApplicant',
                'confirmButtonText' => $confirmText // <-- [LÍNEA AÑADIDA]
            ]);
        }
    }

    /**
     * Aprueba al postulante y crea el registro de Estudiante.
     */
    #[On('approveApplicant')]
    public function approveApplicant(int $id) // Mantenemos el $id corregido de la fase anterior
    {
        $user = User::find($id);
        $applicant = $user?->applicant;

        if (!$user || !$applicant) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se encontró al postulante.']);
            return;
        }

        $existingStudent = Student::where('user_id', $user->id)->exists();
        if ($existingStudent) {
            $this->dispatch('swal', ['icon' => 'info', 'title' => 'Acción Requerida', 'text' => 'Este usuario ya tiene un registro de estudiante.']);
            return;
        }

        try {
            DB::transaction(function () use ($user, $applicant) {
                
                // --- [INICIO DE LA CORRECCIÓN] ---
                // Lógica para generar el nuevo código de estudiante
                $currentYear = date('Y');
                
                // 1. Buscar el último estudiante de este año
                $lastStudent = Student::where('code', 'like', "E{$currentYear}-%")
                                        ->orderBy('code', 'desc')
                                        ->first();
                
                $nextNumber = 1;
                if ($lastStudent) {
                    // 2. Extraer el número (ej. E2025-00001 -> 1)
                    $lastNumber = (int)substr($lastStudent->code, 6); // Obtiene el "00001" y lo convierte a 1
                    $nextNumber = $lastNumber + 1;
                }
                
                // 3. Formatear el nuevo código con 5 dígitos
                $newStudentCode = 'E' . $currentYear . '-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
                // --- [FIN DE LA CORRECCIÓN] ---


                // 2. Crear el Estudiante (AÑADIENDO EL NUEVO CÓDIGO)
                Student::create([
                    'user_id' => $user->id,
                    'applicant_id' => $applicant->id,
                    'career_id' => $applicant->career_id,
                    'study_plan_id' => $applicant->study_plan_id,
                    'code' => $newStudentCode, // <-- ¡CAMPO AÑADIDO!
                    'current_semester' => 1,
                    'academic_status' => 'regular',
                    'admission_date' => now(),
                    'accumulated_credits' => 0,    // (Aseguramos valores por defecto)
                    'weighted_average' => 0.00,  // (Aseguramos valores por defecto)
                ]);

                // 3. Actualizar el estado del Postulante
                $applicant->update(['application_status' => 'approved']);
            });

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'El postulante ha sido aprobado y registrado como estudiante.',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo completar el proceso: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $query = User::query()
            ->role('Estudiante') // Cargamos a todos los que tienen rol 'Estudiante'
            ->whereHas('applicant') // Pero que *solo* tengan perfil de 'applicant'
            ->whereDoesntHave('student') // Y que *no* tengan aún perfil de 'student'
            ->with(['applicant.career']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhereHas('applicant', fn ($sq) => $sq->where('code', 'like', '%'.$this->search.'%'));
            });
        }
        
        $users = $query->orderBy('name')->paginate(10);
        
        return view('livewire.pages.admission.applicant-manager', [
            'users' => $users,
        ]);
    }
}