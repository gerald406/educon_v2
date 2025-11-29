<?php

namespace App\Livewire\Pages\Security;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

#[Layout('layouts.app')]
class RoleManager extends Component
{
    // --- FORMULARIO ---
    public $name = '';
    public $selectedPermissions = []; // Array de IDs de permisos seleccionados

    // --- ESTADO ---
    public ?Role $editingRole = null;
    public $isModalOpen = false;

    // --- DATOS ---
    public $allPermissionsGrouped = [];

    public function mount()
    {
        $this->loadPermissions();
    }

    public function loadPermissions()
    {
        // Agrupar permisos por "módulo" (la segunda parte del nombre, ej: gestionar-docentes -> docentes)
        // O podemos agruparlos manualmente para que se vea bonito.
        // Haremos una agrupación manual basada en palabras clave para mejor UX.

        $permissions = Permission::all();
        $grouped = [
            'Configuración' => [],
            'Académico' => [],
            'Personas (Doc/Est)' => [],
            'Matrícula y Procesos' => [],
            'Tesorería' => [],
            'Admisión' => [],
            'Reportes y Cert.' => [],
            'Otros' => [],
        ];

        foreach ($permissions as $perm) {
            $n = $perm->name;
            if (str_contains($n, 'institucion') || str_contains($n, 'configuracion') || str_contains($n, 'roles')) $grouped['Configuración'][] = $perm;
            elseif (str_contains($n, 'estructura') || str_contains($n, 'prerrequisitos') || str_contains($n, 'silabo')) $grouped['Académico'][] = $perm;
            elseif (str_contains($n, 'docentes') || str_contains($n, 'estudiantes')) $grouped['Personas (Doc/Est)'][] = $perm;
            elseif (str_contains($n, 'periodos') || str_contains($n, 'carga') || str_contains($n, 'horarios') || str_contains($n, 'matricul') || str_contains($n, 'reincorporacion')) $grouped['Matrícula y Procesos'][] = $perm;
            elseif (str_contains($n, 'pagos') || str_contains($n, 'caja') || str_contains($n, 'tramites') || str_contains($n, 'comprobantes') || str_contains($n, 'correlativos')) $grouped['Tesorería'][] = $perm;
            elseif (str_contains($n, 'admision') || str_contains($n, 'postulante')) $grouped['Admisión'][] = $perm;
            elseif (str_contains($n, 'reporte') || str_contains($n, 'acta') || str_contains($n, 'certificacion') || str_contains($n, 'meritos')) $grouped['Reportes y Cert.'][] = $perm;
            else $grouped['Otros'][] = $perm;
        }

        $this->allPermissionsGrouped = $grouped;
    }

    // --- CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(Role $role)
    {
        if ($role->name === 'Administrador' || $role->name === 'Super Admin') {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Protegido', 'text' => 'El rol de Administrador principal no se puede editar por seguridad.']);
            return;
        }

        $this->editingRole = $role;
        $this->name = $role->name;
        // Cargar permisos actuales del rol (IDs)
        $this->selectedPermissions = $role->permissions->pluck('name')->toArray(); // Spatie usa names o ids, usaremos names para sync

        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|unique:roles,name,' . ($this->editingRole?->id ?? 'NULL'),
            'selectedPermissions' => 'array'
        ]);

        DB::transaction(function () {
            $role = Role::updateOrCreate(
                ['id' => $this->editingRole?->id],
                ['name' => $this->name, 'guard_name' => 'web']
            );

            // Sincronizar permisos (elimina los que no estén marcados y agrega los nuevos)
            $role->syncPermissions($this->selectedPermissions);
            // [NUEVA LÍNEA - SOLUCIÓN PERMANENTE]
            // Forzar el borrado de la caché de permisos al guardar
            app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
            
        });

        $this->isModalOpen = false;
        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Guardado!', 'text' => 'Rol y permisos actualizados correctamente.']);
        $this->resetForm();
    }

    public function deleteRole($roleId)
    {
        $role = Role::find($roleId);
        if ($role->name === 'Administrador' || $role->users()->count() > 0) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'No se puede eliminar', 'text' => 'Este rol es crítico o tiene usuarios asignados.']);
            return;
        }

        $role->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Rol eliminado.']);
    }

    public function resetForm()
    {
        $this->reset('name', 'selectedPermissions', 'editingRole');
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function render()
    {
        return view('livewire.pages.security.role-manager', [
            'roles' => Role::withCount('users')->orderBy('name')->get()
        ]);
    }
}
