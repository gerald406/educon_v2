<?php

namespace App\Livewire\Pages\People\Students;

use App\Models\Career;
use App\Models\Student;
use App\Models\StudyPlan;
use App\Models\User;
use Illuminate\Database\QueryException; // [CORREGIDO] Importación correcta
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StudentManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO (ANIDADAS) ---
    public $user = [
        'name' => '',
        'email' => '',
        'password' => '',
    ];
    
    public $student = [
        'code' => '',
        'admission_date' => '',
        'academic_status' => 'regular',
        'current_semester' => 1,
    ];

    // --- PROPIEDADES DE ESTADO ---
    public ?User $editingUser = null;
    public ?Student $editingStudent = null;
    public $isModalOpen = false;
    public $search = '';

    // --- PROPIEDADES PARA DROPDOWNS DEPENDIENTES ---
    public Collection $careers;
    public Collection $availableStudyPlans;
    public $selectedCareerId = '';
    public $selectedStudyPlanId = '';

    /**
     * Hook 'mount': Carga los datos para los dropdowns.
     */
    public function mount()
    {
        $this->careers = Career::where('status', 'active')->pluck('name', 'id');
        $this->availableStudyPlans = collect();

        if ($this->careers->count() > 0) {
            $this->selectedCareerId = $this->careers->keys()->first();
            $this->updateAvailableStudyPlans();
        }
    }

    /**
     * Hook: Filtra los planes de estudio cuando cambia la carrera.
     */
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

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        $rules = [
            'user.name' => 'required|string|max:255',
            'student.admission_date' => 'required|date',
            'student.academic_status' => 'required|in:regular,irregular,graduated,withdrawn,enrollment_reserved',
            'student.current_semester' => 'required|integer|min:1|max:12',
            'selectedCareerId' => 'required|exists:careers,id',
            'selectedStudyPlanId' => 'required|exists:study_plans,id',
            
            'student.code' => [
                'required', 'string', 'max:20',
                Rule::unique('students', 'code')->ignore($this->editingStudent?->id)
            ],
            'user.email' => [
                'required', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($this->editingUser?->id)
            ],
            'user.password' => $this->editingUser ? 'nullable|min:8' : 'required|min:8',
        ];

        return $rules;
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(User $user)
    {
        $this->editingUser = $user;
        $this->editingStudent = $user->student;

        $this->user['name'] = $user->name;
        $this->user['email'] = $user->email;
        $this->user['password'] = '';

        $this->student = $user->student->only(
            'code', 'academic_status', 'current_semester'
        );
        $this->student['admission_date'] = $user->student->admission_date->format('Y-m-d');
        
        $this->selectedCareerId = $user->student->career_id;
        $this->updateAvailableStudyPlans();
        $this->selectedStudyPlanId = $user->student->study_plan_id;
        
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset('user', 'student', 'editingUser', 'editingStudent');
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
                // 1. Preparar datos del Usuario
                $userData = [
                    'name' => $data['user']['name'],
                    'email' => $data['user']['email'],
                    // [CORREGIDO] Ya no usamos user_type
                    // 'user_type' => 'student',
                ];
                if (!empty($data['user']['password'])) {
                    $userData['password'] = Hash::make($data['user']['password']);
                }

                // 2. Crear o Actualizar Usuario
                $user = $this->editingUser ?? new User();
                $user->fill($userData);
                $user->save();
                
                // [NUEVO] Asignar el rol si es un usuario nuevo
                if (!$this->editingUser) {
                    $user->assignRole('Estudiante');
                }
                
                // 3. Preparar datos del Estudiante
                $studentData = $data['student'];
                $studentData['user_id'] = $user->id;
                $studentData['career_id'] = $this->selectedCareerId;
                $studentData['study_plan_id'] = $this->selectedStudyPlanId;
                
                if (!$this->editingStudent) {
                    $studentData['accumulated_credits'] = 0;
                    $studentData['weighted_average'] = 0.00;
                }

                // 4. Crear o Actualizar Estudiante
                $student = $this->editingStudent ?? new Student();
                $student->fill($studentData);
                $student->save();
            });

            $this->closeModal();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Hecho!',
                'text' => 'Estudiante guardado correctamente.',
            ]);

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al guardar',
                'text' => $e->getMessage(),
            ]);
        }
    }

    public function confirmDelete(int $userId)
    {
        $this->dispatch('swal:confirm', [
            'id' => $userId,
            'title' => '¿Eliminar Estudiante?',
            'text' => 'Esto eliminará al usuario y su perfil de estudiante (matrículas, notas, etc.).',
            'onConfirmed' => 'deleteStudent'
        ]);
    }

    #[On('deleteStudent')]
    public function deleteStudent(int $id)
    {
        try {
            User::findOrFail($id)->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El estudiante ha sido eliminado.',
            ]);
        } catch (QueryException $e) { // [CORREGIDO] Usar la importación correcta
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se puede eliminar, es probable que tenga matrículas o notas asociadas.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $query = User::query()
            // [CORREGIDO] Buscar por rol, no por user_type
            ->role('Estudiante') 
            ->with(['student.career', 'student.studyPlan']);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('student', function ($subQuery) {
                    $subQuery->where('code', 'like', '%' . $this->search . '%');
                });
            });
        }
        
        $users = $query->orderBy('name')->paginate(10);

        return view('livewire.pages.people.students.student-manager', [
            'users' => $users,
        ]);
    }
}