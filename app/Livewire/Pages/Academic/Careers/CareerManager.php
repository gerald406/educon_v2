<?php

namespace App\Livewire\Pages\Academic\Careers;

use App\Actions\Academic\CreateCareer;
use App\Actions\Academic\UpdateCareer;
use App\Models\Career;
use App\Models\Institution;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CareerManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $institution_id = '';
    public $code = '';
    public $name = '';
    public $duration_semesters = 6;
    public $degree_awarded = '';
    public $status = 'active';

    // --- PROPIEDADES DE ESTADO ---
    public ?Career $editingCareer = null;
    public $isModalOpen = false;
    public $search = '';

    // Colección para el dropdown de instituciones
    public Collection $institutions;

    /**
     * Hook 'mount': Se ejecuta cuando el componente se carga.
     * Carga las instituciones para el dropdown.
     */
    public function mount()
    {
        $this->institutions = Institution::where('status', 'active')->pluck('name', 'id');
        // Asignar la primera institución por defecto si no se está editando
        if (!$this->editingCareer && $this->institutions->count() > 0) {
            $this->institution_id = $this->institutions->keys()->first();
        }
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(Career $career)
    {
        $this->editingCareer = $career;
        $this->fill($career->only(
            'institution_id', 'code', 'name', 'duration_semesters', 
            'degree_awarded', 'status'
        ));
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset('code', 'name', 'duration_semesters', 'degree_awarded', 'status', 'editingCareer');
        $this->resetValidation();
        // Re-asignar la institución por defecto
        if ($this->institutions->count() > 0) {
            $this->institution_id = $this->institutions->keys()->first();
        }
    }

    public function save(CreateCareer $creator, UpdateCareer $updater)
    {
        $input = [
            'institution_id' => $this->institution_id,
            'code' => $this->code,
            'name' => $this->name,
            'duration_semesters' => $this->duration_semesters,
            'degree_awarded' => $this->degree_awarded,
            'status' => $this->status,
        ];

        if ($this->editingCareer) {
            $updater->update($this->editingCareer, $input);
            $message = 'Programa actualizado correctamente.';
        } else {
            $creator->create($input);
            $message = 'Programa creado correctamente.';
        }
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => $message,
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Programa?',
            'text' => 'Esto eliminará el programa. ¿Continuar?',
            'onConfirmed' => 'deleteCareer'
        ]);
    }

    #[On('deleteCareer')]
    public function deleteCareer(int $id)
    {
        try {
            Career::findOrFail($id)->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El programa ha sido eliminado.',
            ]);
        } catch (QueryException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se puede eliminar, es probable que tenga planes de estudio asociados.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $query = Career::with('institution') // Carga ansiosa (Eager Loading)
                       ->where('institution_id', $this->institutions->keys()->first()); // Filtra por la institución

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }
        
        $careers = $query->orderBy('name')->paginate(10);

        return view('livewire.pages.academic.careers.career-manager', [
            'careers' => $careers,
        ]);
    }
}
