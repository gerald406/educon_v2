<?php

namespace App\Livewire\Pages\Services\Library;

use App\Models\LibraryLoan;
use App\Models\LibraryResource;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class LibraryLoanManager extends Component
{
    use WithPagination;

    // --- BÚSQUEDA Y SELECCIÓN ---
    public $userSearch = '';
    public $resourceSearch = '';
    public Collection $usersFound;
    public Collection $resourcesFound;

    public ?User $selectedUser = null;
    public ?LibraryResource $selectedResource = null;

    // --- FORMULARIO DE PRÉSTAMO ---
    public $due_date = '';
    public $notes = '';

    // --- TABLA DE PRÉSTAMOS ---
    public $search = ''; // Búsqueda en la tabla de préstamos

    public function mount()
    {
        $this->usersFound = collect();
        $this->resourcesFound = collect();
        $this->due_date = now()->addWeeks(1)->format('Y-m-d'); // Préstamo por 1 semana
    }

    /**
     * Hook: Busca usuarios (docentes o estudiantes).
     */
    /**
     * Hook: Busca usuarios (docentes o estudiantes).
     * [VERSIÓN MEJORADA] Busca por nombre, email, cód. estudiante o cód. docente.
     */
    public function updatedUserSearch($value)
    {
        if (strlen($value) < 3) {
            $this->usersFound = collect();
            return;
        }

        $this->usersFound = User::query()
            ->where(function ($query) use ($value) {
                // 1. Buscar en la tabla 'users'
                $query->where('name', 'like', '%' . $value . '%')
                      ->orWhere('email', 'like', '%' . $value . '%');
            })
            // 2. Buscar en la tabla 'students' relacionada
            ->orWhereHas('student', function ($query) use ($value) {
                $query->where('code', 'like', '%' . $value . '%');
            })
            // 3. Buscar en la tabla 'teachers' relacionada
            ->orWhereHas('teacher', function ($query) use ($value) {
                $query->where('code', 'like', '%' . $value . '%');
            })
            ->take(5)
            ->get();
    }
    /* public function updatedUserSearch($value)
    {
        if (strlen($value) < 3) {
            $this->usersFound = collect();
            return;
        }
        $this->usersFound = User::whereIn('id', function($query) {
                $query->select('user_id')->from('students')->orWhereIn('user_id', function($q){
                    $q->select('user_id')->from('teachers');
                });
            })
            ->where(function($query) use ($value) {
                $query->where('name', 'like', '%'.$value.'%')
                    ->orWhere('email', 'like', '%'.$value.'%');
            })
            ->take(5)
            ->get();
    } */


    /**
     * Hook: Busca recursos (libros, etc.) que estén disponibles.
     */
    public function updatedResourceSearch($value)
    {
        if (strlen($value) < 3) {
            $this->resourcesFound = collect();
            return;
        }
        $this->resourcesFound = LibraryResource::where('status', 'available')
            ->where('copies_available', '>', 0)
            ->where(function($query) use ($value) {
                $query->where('title', 'like', '%'.$value.'%')
                      ->orWhere('code', 'like', '%'.$value.'%')
                      ->orWhere('author', 'like', '%'.$value.'%');
            })
            ->take(5)
            ->get();
    }

    // --- ACCIONES DE SELECCIÓN ---

    public function selectUser(User $user)
    {
        $this->selectedUser = $user;
        $this->userSearch = $user->name;
        $this->usersFound = collect();
    }

    public function selectResource(LibraryResource $resource)
    {
        $this->selectedResource = $resource;
        $this->resourceSearch = $resource->title . ' (Cód: ' . $resource->code . ')';
        $this->resourcesFound = collect();
    }

    public function resetSelection()
    {
        $this->selectedUser = null;
        $this->selectedResource = null;
        $this->userSearch = '';
        $this->resourceSearch = '';
        $this->notes = '';
        $this->due_date = now()->addWeeks(1)->format('Y-m-d');
        $this->resetErrorBag();
    }

    /**
     * Acción principal: Registrar el Préstamo.
     */
    public function saveLoan()
    {
        $this->validate([
            'selectedUser' => 'required',
            'selectedResource' => 'required',
            'due_date' => 'required|date|after:today',
        ], [
            'selectedUser.required' => 'Debe seleccionar un usuario.',
            'selectedResource.required' => 'Debe seleccionar un recurso de la biblioteca.',
        ]);

        // Doble chequeo de disponibilidad
        if ($this->selectedResource->copies_available <= 0) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No hay copias disponibles de este recurso.']);
            $this->resetSelection();
            return;
        }

        try {
            DB::transaction(function () {
                // 1. Crear el Préstamo
                LibraryLoan::create([
                    'library_resource_id' => $this->selectedResource->id,
                    'user_id' => $this->selectedUser->id,
                    'loan_date' => now(),
                    'due_date' => $this->due_date,
                    'status' => 'active',
                    'notes' => $this->notes,
                ]);

                // 2. Decrementar el stock del recurso
                $this->selectedResource->decrement('copies_available');

                // 3. Actualizar estado del recurso si ya no hay copias
                if ($this->selectedResource->copies_available == 0) {
                    $this->selectedResource->update(['status' => 'borrowed']);
                }
            });

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Préstamo registrado correctamente.',
            ]);
            $this->resetSelection();

        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        // Cargar préstamos activos o vencidos
        $query = LibraryLoan::with(['user', 'libraryResource'])
            ->whereIn('status', ['active', 'overdue']);

        if ($this->search) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
                ->orWhereHas('libraryResource', fn($q) => $q->where('title', 'like', '%'.$this->search.'%'));
        }

        $activeLoans = $query->orderBy('due_date', 'asc')->paginate(10);
        
        return view('livewire.pages.services.library.library-loan-manager', [
            'activeLoans' => $activeLoans,
        ]);
    }
}