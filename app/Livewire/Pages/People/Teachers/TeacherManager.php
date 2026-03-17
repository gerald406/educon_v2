<?php

namespace App\Livewire\Pages\People\Teachers;

use App\Models\Career;
use App\Models\CareerCoordinator;
use App\Models\Institution;
use App\Models\Teacher;
use App\Models\User;
use App\Services\PersonDataService;
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
    public $searchDni        = '';
    public $document_number  = '';
    public $name             = '';
    public $paternal_surname = '';
    public $maternal_surname = '';
    public $email            = '';
    public $is_new_user      = true;

    // --- Datos de Perfil (Docente) ---
    public $institution_id   = '';
    public $code             = '';
    public $academic_degree  = '';
    public $specialty        = '';
    public $contract_type    = 'contracted';
    public $hire_date        = '';
    public $preparation_day  = 'monday';
    public $status           = 'active';

    // --- Coordinador ---
    public bool $is_coordinator      = false;
    public $selectedCareerId         = null;

    // --- Estado ---
    public ?Teacher $editingTeacher  = null;
    public ?User    $editingUser     = null;
    public bool     $isModalOpen     = false;
    public $search                   = '';

    // --- Catálogos ---
    public $careers = [];

    protected function personService()
    {
        return new PersonDataService();
    }

    public function mount()
    {
        $inst = Institution::where('status', 'active')->first();
        $this->institution_id = $inst?->id;

        $this->careers = Career::where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    public function rules()
    {
        $userId    = $this->editingUser    ? $this->editingUser->id    : null;
        $teacherId = $this->editingTeacher ? $this->editingTeacher->id : null;

        $rules = [
            'document_number'  => [
                'required',
                'digits:8',
                Rule::unique('users', 'document_number')->ignore($userId)
            ],
            'name'             => 'required|string|max:255',
            'paternal_surname' => 'required|string|max:255',
            'maternal_surname' => 'required|string|max:255',
            'email'            => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'institution_id'   => 'required|exists:institutions,id',
            'code'             => [
                'required',
                'max:20',
                Rule::unique('teachers', 'code')->ignore($teacherId)
            ],
            'contract_type'    => 'required|in:permanent,contracted,hourly',
            'status'           => 'required|in:active,leave,terminated',
        ];

        // Si marcó como coordinador, la carrera es obligatoria
        if ($this->is_coordinator) {
            $rules['selectedCareerId'] = 'required|exists:careers,id';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'selectedCareerId.required' => 'Debes seleccionar una carrera para el Coordinador.',
            'selectedCareerId.exists'   => 'La carrera seleccionada no es válida.',
        ];
    }

    // ============================================
    // BÚSQUEDA POR DNI
    // ============================================

    public function searchPersonByDni()
    {
        $this->validate(['searchDni' => 'required|digits:8']);
        $this->document_number = $this->searchDni;

        $user = User::where('document_number', $this->searchDni)->first();

        if ($user) {
            $this->fillUserData($user);
            $this->is_new_user = false;

            // Si ya es coordinador, cargar su carrera
            $coordinator = CareerCoordinator::where('user_id', $user->id)
                ->where('is_active', true)->first();
            if ($coordinator) {
                $this->is_coordinator    = true;
                $this->selectedCareerId  = $coordinator->career_id;
            }

            $this->dispatch('swal', [
                'icon'  => 'info',
                'title' => 'Encontrado',
                'text'  => 'El usuario ya existe en el sistema.'
            ]);
        } else {
            $apiData = $this->personService()->search($this->searchDni);

            if ($apiData) {
                $this->name             = $apiData['nombres'];
                $this->paternal_surname = $apiData['apellido_paterno'];
                $this->maternal_surname = $apiData['apellido_materno'];
                $this->email            = '';
                $this->is_new_user      = true;
                $this->editingUser      = null;

                $this->dispatch('swal', [
                    'icon'  => 'success',
                    'title' => 'Encontrado',
                    'text'  => 'Datos recuperados de RENIEC/API.'
                ]);
            } else {
                $this->name             = '';
                $this->paternal_surname = '';
                $this->maternal_surname = '';
                $this->is_new_user      = true;
                $this->editingUser      = null;

                $this->dispatch('swal', [
                    'icon'  => 'warning',
                    'title' => 'No encontrado',
                    'text'  => 'DNI no encontrado. Ingrese los datos manualmente.'
                ]);
            }
        }
    }

    public function fillUserData(User $user)
    {
        $this->editingUser     = $user;
        $this->document_number = $user->document_number;
        $this->name            = $user->name;
        $parts                 = explode(' ', $user->lastname ?? '');
        $this->paternal_surname = $parts[0] ?? '';
        $this->maternal_surname = isset($parts[1])
            ? implode(' ', array_slice($parts, 1))
            : '';
        $this->email = $user->email;
    }

    // ============================================
    // CRUD
    // ============================================

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

        $this->fillUserData($teacher->user);
        $this->searchDni = $this->document_number;

        // Perfil docente
        $this->institution_id  = $teacher->institution_id;
        $this->code            = $teacher->code;
        $this->academic_degree = $teacher->academic_degree;
        $this->specialty       = $teacher->specialty;
        $this->contract_type   = $teacher->contract_type;
        $this->hire_date       = $teacher->hire_date;
        $this->preparation_day = $teacher->preparation_day;
        $this->status          = $teacher->status;

        // Cargar estado de coordinador
        $coordinator = CareerCoordinator::where('user_id', $teacher->user_id)
            ->where('is_active', true)
            ->first();

        $this->is_coordinator   = (bool) $coordinator;
        $this->selectedCareerId = $coordinator?->career_id;

        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->authorize('gestionar-docentes');
        $this->validate();

        DB::transaction(function () {
            // 1. Crear o actualizar usuario
            $fullLastname = trim($this->paternal_surname . ' ' . $this->maternal_surname);

            $userData = [
                'name'            => $this->name,
                'lastname'        => $fullLastname,
                'document_number' => $this->document_number,
                'email'           => $this->email,
            ];

            if (!$this->editingUser) {
                // Contraseña inicial = DNI
                $userData['password'] = Hash::make($this->document_number);
            }

            $user = User::updateOrCreate(
                ['id' => $this->editingUser?->id],
                $userData
            );

            // 2. Asignar rol Docente (siempre)
            if (!$user->hasRole('Docente')) {
                $user->assignRole('Docente');
            }

            // 3. Gestionar rol Coordinador
            if ($this->is_coordinator) {
                // Asignar rol Coordinador si no lo tiene
                if (!$user->hasRole('Coordinador')) {
                    $user->assignRole('Coordinador');
                }

                // Desactivar coordinador anterior de esa carrera
                CareerCoordinator::where('career_id', $this->selectedCareerId)
                    ->where('user_id', '!=', $user->id)
                    ->where('is_active', true)
                    ->update(['is_active' => false]);

                // Crear o actualizar asignación
                CareerCoordinator::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'career_id'     => $this->selectedCareerId,
                        'is_active'     => true,
                        'assigned_date' => now()->toDateString(),
                    ]
                );
            } else {
                // Quitar rol Coordinador si lo tenía
                if ($user->hasRole('Coordinador')) {
                    $user->removeRole('Coordinador');
                }

                // Desactivar asignación de coordinador
                CareerCoordinator::where('user_id', $user->id)
                    ->update(['is_active' => false]);
            }

            // 4. Crear o actualizar perfil docente
            Teacher::updateOrCreate(
                ['id' => $this->editingTeacher?->id],
                [
                    'user_id'        => $user->id,
                    'institution_id' => $this->institution_id,
                    'code'           => $this->code,
                    'academic_degree' => $this->academic_degree,
                    'specialty'      => $this->specialty,
                    'contract_type'  => $this->contract_type,
                    'hire_date'      => $this->hire_date ?: null,
                    'preparation_day' => $this->preparation_day ?: null,
                    'status'         => $this->status,
                ]
            );
        });

        $this->isModalOpen = false;
        $msg = $this->editingTeacher
            ? 'Docente actualizado correctamente.'
            : 'Docente registrado. Contraseña inicial: DNI del docente.';

        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => 'Éxito',
            'text'  => $msg
        ]);

        $this->resetInput();
    }

    public function confirmDelete($id)
    {
        $this->authorize('gestionar-docentes');
        $this->dispatch('confirm-delete-teacher', id: $id);
    }

    #[On('deleteTeacher')]
    public function deleteTeacher($id)
    {
        try {
            $teacher = Teacher::with('user')->findOrFail($id);

            DB::transaction(function () use ($teacher) {
                // Desactivar coordinador si existe
                CareerCoordinator::where('user_id', $teacher->user_id)
                    ->update(['is_active' => false]);

                // Eliminar usuario (cascada lógica elimina teacher por SoftDelete)
                $teacher->user->delete();
            });

            $this->dispatch('swal', [
                'icon'  => 'success',
                'title' => 'Eliminado',
                'text'  => 'Docente eliminado correctamente.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Error',
                'text'  => 'No se puede eliminar este docente.'
            ]);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->editingTeacher   = null;
        $this->editingUser      = null;
        $this->searchDni        = '';
        $this->document_number  = '';
        $this->name             = '';
        $this->paternal_surname = '';
        $this->maternal_surname = '';
        $this->email            = '';
        $this->code             = '';
        $this->academic_degree  = '';
        $this->specialty        = '';
        $this->contract_type    = 'contracted';
        $this->hire_date        = '';
        $this->preparation_day  = 'monday';
        $this->status           = 'active';
        $this->is_coordinator   = false;
        $this->selectedCareerId = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        $query = Teacher::with(['user', 'user.careerCoordinator.career'])
            ->when($this->search, function ($q) {
                $q->whereHas('user', function ($uq) {
                    $uq->where('name', 'like', "%{$this->search}%")
                        ->orWhere('lastname', 'like', "%{$this->search}%")
                        ->orWhere('document_number', 'like', "%{$this->search}%");
                });
            });

        return view('livewire.pages.people.teachers.teacher-manager', [
            'teachers' => $query->orderBy('status')->paginate(10),
            'careers'  => $this->careers,
        ]);
    }
}
