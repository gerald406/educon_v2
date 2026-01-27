<?php

namespace App\Livewire\Pages\People\Teachers;

use App\Models\Institution;
use App\Models\Teacher;
use App\Models\User;
use App\Services\PersonDataService; // Importar el servicio
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TeacherManager extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    // --- Datos de Usuario (Cuenta) ---
    public $searchDni = ''; // Para el input de búsqueda
    public $document_number = ''; // DNI final a guardar
    public $name = '';
    public $paternal_surname = ''; // Nuevo: Apellido Paterno
    public $maternal_surname = ''; // Nuevo: Apellido Materno
    public $email = '';
    public $is_new_user = true; // Flag para saber si creamos o editamos usuario

    // --- Datos de Perfil (Docente) ---
    public $institution_id = '';
    public $code = '';
    public $academic_degree = '';
    public $specialty = '';
    public $contract_type = 'contracted';
    public $hire_date = '';
    public $preparation_day = 'monday';
    public $status = 'active';

    // --- Estado ---
    public ?Teacher $editingTeacher = null;
    public ?User $editingUser = null; // Guardar referencia al usuario si existe
    public $isModalOpen = false;
    public $search = '';

    // Inyectar servicio manualmente o usar resolve en el método
    protected function personService()
    {
        return new PersonDataService();
    }

    public function mount()
    {
        $inst = Institution::where('status', 'active')->first();
        $this->institution_id = $inst?->id;
    }

    public function rules()
    {
        $userId = $this->editingUser ? $this->editingUser->id : null;
        $teacherId = $this->editingTeacher ? $this->editingTeacher->id : null;

        return [
            // User Data
            'document_number' => ['required', 'digits:8', Rule::unique('users', 'document_number')->ignore($userId)],
            'name' => 'required|string|max:255',
            'paternal_surname' => 'required|string|max:255',
            'maternal_surname' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],

            // Teacher Data
            'institution_id' => 'required|exists:institutions,id',
            'code' => ['required', 'max:20', Rule::unique('teachers', 'code')->ignore($teacherId)],
            'contract_type' => 'required|in:permanent,contracted,hourly',
            'status' => 'required|in:active,leave,terminated',
        ];
    }

    // --- LÓGICA DE BÚSQUEDA ---
    public function searchPersonByDni()
    {
        $this->validate(['searchDni' => 'required|digits:8']);
        $this->document_number = $this->searchDni; // Sincronizar

        // 1. Buscar en BD Local
        $user = User::where('document_number', $this->searchDni)->first();

        if ($user) {
            $this->fillUserData($user);
            $this->is_new_user = false;
            $this->dispatch('swal', ['icon' => 'info', 'title' => 'Encontrado', 'text' => 'El usuario ya existe en el sistema.']);
        } else {
            // 2. Buscar en API Externa
            $apiData = $this->personService()->search($this->searchDni);

            if ($apiData) {
                $this->name = $apiData['nombres'];
                $this->paternal_surname = $apiData['apellido_paterno'];
                $this->maternal_surname = $apiData['apellido_materno'];
                $this->email = ''; // Limpiar email para que lo ingrese
                $this->is_new_user = true;
                $this->editingUser = null;

                $this->dispatch('swal', ['icon' => 'success', 'title' => 'Encontrado', 'text' => 'Datos recuperados de RENIEC/API.']);
            } else {
                // 3. No encontrado: Permitir ingreso manual
                $this->name = '';
                $this->paternal_surname = '';
                $this->maternal_surname = '';
                $this->is_new_user = true;
                $this->editingUser = null;

                $this->dispatch('swal', ['icon' => 'warning', 'title' => 'No encontrado', 'text' => 'DNI no encontrado. Por favor ingrese los datos manualmente.']);
            }
        }
    }

    public function fillUserData(User $user)
    {
        $this->editingUser = $user;
        $this->document_number = $user->document_number;
        $this->name = $user->name;

        // Separar apellidos (asumiendo que están guardados como "Paterno Materno")
        $parts = explode(' ', $user->lastname);
        $this->paternal_surname = $parts[0] ?? '';
        $this->maternal_surname = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '';

        $this->email = $user->email;
    }

    // --- ACCIONES ---

    public function create()
    {
        $this->authorize('gestionar-docentes');
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function edit(Teacher $teacher)
    {
        $this->authorize('gestionar-docentes');
        $this->editingTeacher = $teacher;

        // Cargar usuario
        $this->fillUserData($teacher->user);
        $this->searchDni = $this->document_number; // Para visualización

        // Cargar perfil
        $this->institution_id = $teacher->institution_id;
        $this->code = $teacher->code;
        $this->academic_degree = $teacher->academic_degree;
        $this->specialty = $teacher->specialty;
        $this->contract_type = $teacher->contract_type;
        $this->hire_date = $teacher->hire_date;
        $this->preparation_day = $teacher->preparation_day;
        $this->status = $teacher->status;

        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->authorize('gestionar-docentes');
        $validated = $this->validate();

        DB::beginTransaction();
        try {
            // 1. Crear o Actualizar Usuario
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

            $user = User::updateOrCreate(
                ['id' => $this->editingUser?->id],
                $userData
            );

            if (!$user->hasRole('Docente')) {
                $user->assignRole('Docente');
            }

            // 2. Crear o Actualizar Perfil Docente
            Teacher::updateOrCreate(
                ['id' => $this->editingTeacher?->id],
                [
                    'user_id' => $user->id,
                    'institution_id' => $this->institution_id,
                    'code' => $this->code,
                    'academic_degree' => $this->academic_degree,
                    'specialty' => $this->specialty,
                    'contract_type' => $this->contract_type,

                    // --- CORRECCIÓN AQUÍ ---
                    // Convertimos cadena vacía a NULL
                    'hire_date' => $this->hire_date ?: null,
                    'preparation_day' => $this->preparation_day ?: null,
                    // -----------------------

                    'status' => $this->status,
                ]
            );

            DB::commit();
            $this->isModalOpen = false;
            $msg = $this->editingTeacher ? 'Docente actualizado.' : 'Docente registrado. Contraseña inicial: DNI';
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Éxito', 'text' => $msg]);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function confirmDelete($id)
    {
        $this->authorize('gestionar-docentes');
        $this->dispatch('swal:confirm', [
            'title' => '¿Eliminar Docente?',
            'text' => 'Se eliminará el perfil docente y el acceso al sistema.',
            'id' => $id,
            'method' => 'deleteTeacher'
        ]);
    }

    #[On('deleteTeacher')]
    public function deleteTeacher($id)
    {
        try {
            $teacher = Teacher::with('user')->findOrFail($id);
            $teacher->user->delete(); // Borramos usuario por cascada lógica
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Registros eliminados.']);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se puede eliminar.']);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInput()
    {
        $this->editingTeacher = null;
        $this->editingUser = null;
        $this->searchDni = '';
        $this->document_number = '';
        $this->name = '';
        $this->paternal_surname = '';
        $this->maternal_surname = '';
        $this->email = '';
        $this->code = '';
        $this->academic_degree = '';
        $this->specialty = '';
        $this->contract_type = 'contracted';
        $this->hire_date = '';
        $this->status = 'active';
    }

    public function render()
    {
        $query = Teacher::with('user')
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('name', 'like', "%{$this->search}%")
                        ->orWhere('lastname', 'like', "%{$this->search}%")
                        ->orWhere('document_number', 'like', "%{$this->search}%");
                });
            });

        return view('livewire.pages.people.teachers.teacher-manager', [
            'teachers' => $query->orderBy('status')->paginate(10)
        ]);
    }
}
