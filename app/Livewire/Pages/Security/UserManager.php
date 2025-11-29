<?php

namespace App\Livewire\Pages\Security;

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
    public $name = '';
    public $email = '';
    public $password = '';
    public $selectedRoles = []; // Array de IDs de roles

    // --- ESTADO ---
    public ?User $editingUser = null;
    public $isModalOpen = false;

    // Catálogo
    public $roles = [];

    public function mount()
    {
        // Cargar roles disponibles (excluyendo roles especiales si se desea)
        $this->roles = Role::orderBy('name')->get();
    }

    // --- CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(User $user)
    {
        if ($user->hasRole('Administrador') && $user->id == 1) {
            // Evitar editar al Super Admin principal si se desea proteger
        }

        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->password = ''; // No mostrar password

        // Cargar roles actuales
        $this->selectedRoles = $user->roles->pluck('name')->toArray(); // Usamos nombres para el sync

        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingUser?->id)],
            'password' => $this->editingUser ? 'nullable|min:8' : 'required|min:8',
            'selectedRoles' => 'required|array|min:1'
        ]);

        DB::transaction(function () {
            $data = [
                'name' => $this->name,
                'email' => $this->email,
            ];

            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }

            $user = User::updateOrCreate(
                ['id' => $this->editingUser?->id],
                $data
            );

            // Sincronizar Roles
            $user->syncRoles($this->selectedRoles);
        });

        $this->isModalOpen = false;
        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Guardado!', 'text' => 'Usuario actualizado correctamente.']);
        $this->resetForm();
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        // Protecciones
        if ($user->hasRole('Administrador') || $user->id === auth()->id()) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Acción Denegada', 'text' => 'No puedes eliminar a este usuario.']);
            return;
        }

        $user->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Usuario eliminado.']);
    }

    public function resetForm()
    {
        $this->reset('name', 'email', 'password', 'selectedRoles', 'editingUser');
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function render()
    {
        // Filtrar usuarios: Mostrar solo los que NO son estudiantes NI docentes
        // O mostrar todos y dejar que el admin filtre. 
        // Para este módulo "Staff", es mejor excluir a la masa de estudiantes.

        $query = User::query()
            ->whereDoesntHave('student')
            ->whereDoesntHave('teacher')
            ->with('roles');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.pages.security.user-manager', [
            'users' => $query->orderBy('name')->paginate(10)
        ]);
    }
}
