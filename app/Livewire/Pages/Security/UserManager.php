<?php

namespace App\Livewire\Pages\Security;

use App\Models\Career;
use App\Models\CareerCoordinator;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class UserManager extends Component
{
    use WithPagination;

    // --- BÚSQUEDA ---
    public $search = '';

    // --- FORMULARIO ---
    public $name        = '';
    public $lastname    = '';
    public $email       = '';
    public $password    = '';
    public $selectedRoles = [];

    // --- COORDINADOR ---
    public $selectedCareerId = null; // Carrera asignada al coordinador

    // --- ESTADO ---
    public ?User $editingUser = null;
    public $isModalOpen       = false;

    // --- CATÁLOGOS ---
    public $roles   = [];
    public $careers = [];

    // ============================================
    // COMPUTED: detectar si el rol Coordinador
    // está seleccionado en el formulario
    // ============================================
    public function getIsCoordinatorSelectedProperty(): bool
    {
        return in_array('Coordinador', $this->selectedRoles);
    }

    // CAMBIO en mount()
    public function mount()
    {
        // Roles exclusivos de staff administrativo
        $this->roles = Role::orderBy('name')
            ->whereNotIn('name', ['Docente', 'Estudiante', 'Externo'])
            ->get();

        $this->careers = Career::where('status', 'active')
            ->orderBy('name')
            ->get();
    }

    // ============================================
    // CRUD
    // ============================================

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(User $user)
    {
        $this->editingUser  = $user;
        $this->name         = $user->name;
        $this->lastname     = $user->lastname ?? '';
        $this->email        = $user->email;
        $this->password     = '';

        // Cargar roles actuales
        $this->selectedRoles = $user->roles->pluck('name')->toArray();

        // Si es coordinador, cargar su carrera asignada
        $this->selectedCareerId = $user->careerCoordinator?->career_id;

        $this->isModalOpen = true;
    }

    public function save()
    {
        // AÑADIR al inicio de save(), antes de $rules
        $rolesProhibidos = array_intersect(
            $this->selectedRoles,
            ['Docente', 'Estudiante', 'Externo']
        );

        if (!empty($rolesProhibidos)) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Rol no permitido',
                'text'  => 'Los roles Docente y Estudiante se gestionan desde sus módulos específicos.',
            ]);
            return;
        }
        // Reglas base
        $rules = [
            'name'          => 'required|string|max:255',
            'lastname'      => 'nullable|string|max:255',
            'email'         => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->editingUser?->id)
            ],
            'password'      => $this->editingUser ? 'nullable|min:8' : 'required|min:8',
            'selectedRoles' => 'required|array|min:1',
        ];

        // Si seleccionó rol Coordinador, la carrera es obligatoria
        if ($this->isCoordinatorSelected) {
            $rules['selectedCareerId'] = 'required|exists:careers,id';
        }

        $this->validate($rules, [
            'selectedCareerId.required' => 'Debes seleccionar una carrera para el Coordinador.',
            'selectedCareerId.exists'   => 'La carrera seleccionada no es válida.',
        ]);

        DB::transaction(function () {
            // 1. Crear o actualizar el usuario
            $data = [
                'name'     => $this->name,
                'lastname' => $this->lastname,
                'email'    => $this->email,
            ];

            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            $user = User::updateOrCreate(
                ['id' => $this->editingUser?->id],
                $data
            );

            // 2. Sincronizar roles
            $user->syncRoles($this->selectedRoles);

            // 3. Gestionar asignación de coordinador
            if ($this->isCoordinatorSelected) {
                // Verificar que la carrera no tenga ya otro coordinador activo
                $existingCoordinator = CareerCoordinator::where('career_id', $this->selectedCareerId)
                    ->where('user_id', '!=', $user->id)
                    ->where('is_active', true)
                    ->first();

                if ($existingCoordinator) {
                    // Desactivar al coordinador anterior de esa carrera
                    $existingCoordinator->update(['is_active' => false]);
                }

                // Crear o actualizar la asignación del coordinador
                CareerCoordinator::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'career_id'     => $this->selectedCareerId,
                        'is_active'     => true,
                        'assigned_date' => now()->toDateString(),
                    ]
                );
            } else {
                // Si quitaron el rol Coordinador, desactivar su asignación
                CareerCoordinator::where('user_id', $user->id)
                    ->update(['is_active' => false]);
            }
        });

        $this->isModalOpen = false;
        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => '¡Guardado!',
            'text'  => 'Usuario actualizado correctamente.'
        ]);
        $this->resetForm();
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) return;

        // Protecciones
        if ($user->hasRole('Administrador') || $user->id === auth()->id()) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Acción Denegada',
                'text'  => 'No puedes eliminar a este usuario.'
            ]);
            return;
        }

        DB::transaction(function () use ($user) {
            // Desactivar asignación de coordinador si existe
            CareerCoordinator::where('user_id', $user->id)
                ->update(['is_active' => false]);

            $user->delete();
        });

        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => 'Eliminado',
            'text'  => 'Usuario eliminado correctamente.'
        ]);
    }

    public function resetForm()
    {
        $this->reset(
            'name',
            'lastname',
            'email',
            'password',
            'selectedRoles',
            'selectedCareerId',
            'editingUser'
        );
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function render()
    {
        $query = User::query()
            ->whereDoesntHave('student')
            ->whereDoesntHave('teacher')
            ->with(['roles', 'careerCoordinator.career']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.pages.security.user-manager', [
            'users' => $query->orderBy('name')->paginate(10)
        ]);
    }
}
