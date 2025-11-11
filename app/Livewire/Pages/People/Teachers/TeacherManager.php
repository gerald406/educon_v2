<?php

namespace App\Livewire\Pages\People\Teachers;

use App\Models\Institution;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Database\QueryException as DatabaseQueryException;
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

    // --- PROPIEDADES DEL FORMULARIO (ANIDADAS) ---
    public $user = [
        'name' => '',
        'email' => '',
        'password' => '',
    ];
    
    public $teacher = [
        'code' => '',
        'academic_degree' => '',
        'specialty' => '',
        'contract_type' => 'contracted',
        'preparation_day' => null,
        'status' => 'active',
    ];

    // --- PROPIEDADES DE ESTADO ---
    public ?User $editingUser = null;
    public ?Teacher $editingTeacher = null;
    public $isModalOpen = false;
    public $search = '';
    public $institution_id;

    /**
     * Hook 'mount': Carga la institución principal.
     */
    public function mount()
    {
        $this->institution_id = Institution::first()->id;
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        // Reglas base
        $rules = [
            'user.name' => 'required|string|max:255',
            'teacher.code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('teachers', 'code')->where(fn ($query) => $query->where('institution_id', $this->institution_id))
                    ->ignore($this->editingTeacher?->id)
            ],
            'teacher.academic_degree' => 'nullable|string|max:100',
            'teacher.specialty' => 'nullable|string|max:150',
            'teacher.contract_type' => 'required|in:permanent,contracted,hourly',
            'teacher.preparation_day' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'teacher.status' => 'required|in:active,leave,terminated',
        ];

        // Reglas dinámicas para email y password
        if ($this->editingUser) {
            $rules['user.email'] = 'required|email|max:255|unique:users,email,' . $this->editingUser->id;
            $rules['user.password'] = 'nullable|min:8';
        } else {
            $rules['user.email'] = 'required|email|max:255|unique:users,email';
            $rules['user.password'] = 'required|min:8';
        }

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
        $this->editingTeacher = $user->teacher; 

        $this->user['name'] = $user->name;
        $this->user['email'] = $user->email;
        $this->user['password'] = ''; 

        $this->teacher = $user->teacher->only(
            'code', 'academic_degree', 'specialty', 
            'contract_type', 'preparation_day', 'status'
        );
        
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset('user', 'teacher', 'editingUser', 'editingTeacher');
        $this->resetValidation();
    }

    /**
     * [MÉTODO SAVE CORREGIDO]
     */
    public function save()
    {
        $data = $this->validate();

        try {
            DB::transaction(function () use ($data) {
                // 1. Preparar datos del Usuario
                $userData = [
                    'name' => $data['user']['name'],
                    'email' => $data['user']['email'],
                    // [CORREGIDO] Eliminamos 'user_type'
                ];
                if (!empty($data['user']['password'])) {
                    $userData['password'] = Hash::make($data['user']['password']);
                }

                // 2. Crear o Actualizar Usuario
                $user = $this->editingUser ?? new User();
                $user->fill($userData);
                $user->save();
                
                // [NUEVO] Asignar el rol de Docente
                // (syncRoles se asegura de que solo tenga este rol,
                // si quisiéramos que también sea Coordinador, lo haríamos en otro lado)
                if (!$this->editingUser) {
                     $user->assignRole('Docente');
                }
                
                // 3. Preparar datos del Docente
                $teacherData = $data['teacher'];
                $teacherData['user_id'] = $user->id;
                $teacherData['institution_id'] = $this->institution_id;

                // 4. Crear o Actualizar Docente
                $teacher = $this->editingTeacher ?? new Teacher();
                $teacher->fill($teacherData);
                $teacher->save();
            });

            $this->closeModal();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Hecho!',
                'text' => 'Docente guardado correctamente.',
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
            'title' => '¿Eliminar Docente?',
            'text' => 'Esto eliminará al usuario y su perfil de docente. Esta acción no se puede deshacer.',
            'onConfirmed' => 'deleteTeacher'
        ]);
    }

    #[On('deleteTeacher')]
    public function deleteTeacher(int $id)
    {
        try {
            User::findOrFail($id)->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El docente ha sido eliminado.',
            ]);
        } catch (DatabaseQueryException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se puede eliminar, es probable que esté asociado a carga académica.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    /**
     * [MÉTODO RENDER CORREGIDO]
     */
    public function render()
    {
        $query = User::query()
            // [CORREGIDO] Buscamos por ROL, no por 'user_type'
            ->role('Docente') 
            ->whereHas('teacher', fn($q) => $q->where('institution_id', $this->institution_id))
            ->with('teacher');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhereHas('teacher', function ($subQuery) {
                        $subQuery->where('code', 'like', '%' . $this->search . '%');
                    });
            });
        }
        
        $users = $query->orderBy('name')->paginate(10);

        return view('livewire.pages.people.teachers.teacher-manager', [
            'users' => $users,
        ]);
    }
}