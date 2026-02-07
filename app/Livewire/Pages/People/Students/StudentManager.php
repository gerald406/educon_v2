<?php

namespace App\Livewire\Pages\People\Students;

use App\Models\Career;
use App\Models\Student;
use App\Models\StudyPlan;
use App\Models\User;
use App\Services\PersonDataService; // Importar servicio
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
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
    use AuthorizesRequests;

    // --- User Data ---
    public $searchDni = '';
    public $document_number = '';
    public $name = '';
    public $paternal_surname = '';
    public $maternal_surname = '';
    public $email = '';

    // Extras de User/Applicant
    public $phone = '';
    public $address = '';
    public $gender = 'masculino';
    public $birthday = '';

    // --- Student Profile ---
    public $career_id = '';
    public $study_plan_id = '';
    public $code = '';
    public $current_semester = 1;
    public $admission_date = '';
    public $academic_status = 'regular';

    // --- State ---
    public ?Student $editingStudent = null;
    public ?User $editingUser = null;
    public $isModalOpen = false;
    public $search = '';

    // --- Dropdowns ---
    public Collection $careers;
    public Collection $studyPlans;

    protected function personService()
    {
        return new PersonDataService();
    }

    public function mount()
    {
        $this->careers = Career::where('status', 'active')->pluck('name', 'id');
        $this->studyPlans = collect();
    }

    public function rules()
    {
        $userId = $this->editingUser ? $this->editingUser->id : null;
        $studentId = $this->editingStudent ? $this->editingStudent->id : null;

        return [
            // User
            'document_number' => ['required', 'digits:8', Rule::unique('users', 'document_number')->ignore($userId)],
            'name' => 'required|string|max:255',
            'paternal_surname' => 'required|string|max:255',
            'maternal_surname' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],

            // Extras
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'gender' => 'required|in:masculino,femenino',
            'birthday' => 'nullable|date',

            // Student
            'career_id' => 'required|exists:careers,id',
            'study_plan_id' => 'required|exists:study_plans,id',
            'code' => ['required', 'max:20', Rule::unique('students', 'code')->ignore($studentId)],
            'current_semester' => 'required|integer|min:1|max:6',
            'admission_date' => 'required|date',
            'academic_status' => 'required|in:regular,irregular,graduated,withdrawn,enrollment_reserved',
        ];
    }

    // --- LÓGICA DE BÚSQUEDA ---
    public function searchPersonByDni()
    {
        $this->validate(['searchDni' => 'required|digits:8']);
        $this->document_number = $this->searchDni;

        $user = User::where('document_number', $this->searchDni)->first();

        if ($user) {
            $this->fillUserData($user);
            $this->dispatch('swal', ['icon' => 'info', 'title' => 'Encontrado', 'text' => 'Usuario encontrado en el sistema.']);
        } else {
            $apiData = $this->personService()->search($this->searchDni);

            if ($apiData) {
                $this->name = $apiData['nombres'];
                $this->paternal_surname = $apiData['apellido_paterno'];
                $this->maternal_surname = $apiData['apellido_materno'];
                $this->email = '';
                $this->editingUser = null;
                $this->dispatch('swal', ['icon' => 'success', 'title' => 'Encontrado', 'text' => 'Datos recuperados de API.']);
            } else {
                $this->name = '';
                $this->paternal_surname = '';
                $this->maternal_surname = '';
                $this->editingUser = null;
                $this->dispatch('swal', ['icon' => 'warning', 'title' => 'No Encontrado', 'text' => 'Ingrese datos manualmente.']);
            }
        }
    }

    public function fillUserData(User $user)
    {
        $this->editingUser = $user;
        $this->document_number = $user->document_number;
        $this->name = $user->name;

        $parts = explode(' ', $user->lastname);
        $this->paternal_surname = $parts[0] ?? '';
        $this->maternal_surname = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '';

        $this->email = $user->email;

        // Si ya es estudiante, cargar datos extra si existen en Applicant o Student
        // Priorizamos tabla students si tienes esos campos, si no applicant
        // Asumo que migraste phone/address a students en tu ultimo lote
        if ($this->editingStudent) {
            $this->phone = $this->editingStudent->phone;
            $this->address = $this->editingStudent->address;
            $this->gender = $this->editingStudent->gender;
            $this->birthday = $this->editingStudent->birthday ? $this->editingStudent->birthday->format('Y-m-d') : '';
        }
    }

    public function updatedCareerId($value)
    {
        $this->studyPlans = StudyPlan::where('career_id', $value)->where('status', 'active')->get();
        $this->study_plan_id = '';
    }

    public function create()
    {
        $this->authorize('gestionar-estudiantes');
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function edit(Student $student)
    {
        $this->authorize('gestionar-estudiantes');
        $this->editingStudent = $student;

        $this->fillUserData($student->user);
        $this->searchDni = $this->document_number;

        $this->career_id = $student->career_id;
        $this->updatedCareerId($this->career_id);
        $this->study_plan_id = $student->study_plan_id;

        $this->code = $student->code;
        $this->current_semester = $student->current_semester;
        $this->admission_date = $student->admission_date ? $student->admission_date->format('Y-m-d') : '';
        $this->academic_status = $student->academic_status;

        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->authorize('gestionar-estudiantes');
        $validated = $this->validate();

        DB::beginTransaction();
        try {
            // 1. Usuario
            $fullLastname = trim($this->paternal_surname . ' ' . $this->maternal_surname);

            $userData = [
                'name' => $this->name,
                'lastname' => $fullLastname,
                'document_number' => $this->document_number,
                'email' => $this->email,
            ];

            if (!$this->editingUser) {
                $userData['password'] = Hash::make($this->document_number);
            }

            $user = User::updateOrCreate(['id' => $this->editingUser?->id], $userData);

            if (!$user->hasRole('Estudiante')) {
                $user->assignRole('Estudiante');
            }

            // 2. Estudiante
            Student::updateOrCreate(
                ['id' => $this->editingStudent?->id],
                [
                    'user_id' => $user->id,
                    'career_id' => $this->career_id,
                    'study_plan_id' => $this->study_plan_id,
                    'code' => $this->code,
                    'current_semester' => $this->current_semester,
                    'admission_date' => $this->admission_date,
                    'academic_status' => $this->academic_status,
                    'phone' => $this->phone,
                    'address' => $this->address,
                    'gender' => $this->gender,
                    'birthday' => $this->birthday ?: null,
                ]
            );

            DB::commit();
            $this->isModalOpen = false;
            $msg = $this->editingStudent ? 'Estudiante actualizado.' : 'Estudiante registrado. Contraseña: DNI';
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Éxito', 'text' => $msg]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function confirmDelete($id)
    {
        $this->authorize('gestionar-estudiantes');
        $this->dispatch('swal:confirm', ['title' => '¿Eliminar?', 'text' => 'Se eliminará usuario y datos.', 'id' => $id, 'method' => 'deleteStudent']);
    }

    #[On('deleteStudent')]
    public function deleteStudent($id)
    {
        try {
            $student = Student::findOrFail($id);

            // 1. Validación de seguridad: No borrar si tiene historial académico (Matrículas)
            // Asumiendo que tienes el modelo Enrollment importado
            $hasEnrollments = \App\Models\Enrollment::where('student_id', $student->id)->exists();

            if ($hasEnrollments) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'No permitido',
                    'text' => 'El estudiante tiene matrículas registradas. No se puede eliminar.'
                ]);
                return;
            }

            DB::transaction(function () use ($student) {
                // 2. Guardamos el usuario asociado antes de borrar al estudiante
                $user = $student->user;

                // 3. Borramos PRIMERO al estudiante (El hijo)
                $student->delete();

                // 4. Borramos DESPUÉS al usuario (El padre) si existe
                if ($user) {
                    $user->delete();
                }
            });

            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Estudiante y usuario eliminados correctamente.']);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Error técnico: ' . $e->getMessage()]);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInput()
    {
        $this->editingStudent = null;
        $this->editingUser = null;
        $this->searchDni = '';
        $this->document_number = '';
        $this->name = '';
        $this->paternal_surname = '';
        $this->maternal_surname = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->gender = 'masculino';
        $this->code = '';
        $this->current_semester = 1;
        $this->admission_date = now()->format('Y-m-d');
        $this->studyPlans = collect();
    }

    public function render()
    {
        $query = Student::with(['user', 'career'])
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('name', 'like', "%{$this->search}%")
                        ->orWhere('lastname', 'like', "%{$this->search}%")
                        ->orWhere('document_number', 'like', "%{$this->search}%");
                });
            });

        return view('livewire.pages.people.students.student-manager', [
            'students' => $query->orderBy('code')->paginate(10)
        ]);
    }
}
