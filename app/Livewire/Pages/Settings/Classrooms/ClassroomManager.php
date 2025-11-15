<?php

namespace App\Livewire\Pages\Settings\Classrooms;

use App\Models\ClassroomResource;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
// ELIMINAMOS LA IMPORTACIÓN DE 'Rule'
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

#[Layout('layouts.app')]
class ClassroomManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    // ¡HEMOS QUITADO TODOS LOS ATRIBUTOS #[Rule] DE AQUÍ!
    public $classroom_code = '';
    public $name = '';
    public $building = '';
    public $capacity = 40;
    public $has_projector = false;
    public $has_computers = false;
    public $computer_count = 0;
    public $status = 'available';

    // --- PROPIEDADES DE ESTADO ---
    public ?ClassroomResource $editingClassroom = null;
    public $isModalOpen = false;
    public $search = '';

    /**
     * [NUEVO] MÉTODO DE REGLAS
     * Aquí definimos las reglas de validación.
     * Este método es llamado automáticamente por $this->validate()
     */
    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:100',
            'building' => 'nullable|string|max:100',
            'capacity' => 'required|integer|min:1',
            'has_projector' => 'boolean',
            'has_computers' => 'boolean',
            'computer_count' => 'required_if:has_computers,true|numeric|min:0',
            'status' => 'required|in:available,maintenance,unavailable',
        ];

        // Regla dinámica para 'classroom_code'
        if ($this->editingClassroom) {
            // Si estamos editando, ignoramos el ID del registro actual
            $rules['classroom_code'] = 'required|string|max:20|unique:classroom_resources,classroom_code,' . $this->editingClassroom->id;
        } else {
            // Si estamos creando, debe ser único en toda la tabla
            $rules['classroom_code'] = 'required|string|max:20|unique:classroom_resources,classroom_code';
        }

        return $rules;
    }

    // Hook para resetear computer_count
    public function updatedHasComputers($value) {
        if (!$value) $this->computer_count = 0;
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal() {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    /**
     * [CORREGIDO]
     * Ya no modificamos $this->rules aquí.
     */
    public function openEditModal(ClassroomResource $classroom) {
        $this->editingClassroom = $classroom;
        
        // Simplemente llenamos el formulario. El método rules() se encargará de la lógica.
        $this->fill($classroom->only(
            'classroom_code', 'name', 'building', 'capacity', 
            'has_projector', 'has_computers', 'computer_count', 'status'
        ));
        
        $this->isModalOpen = true;
    }

    public function closeModal() {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    /**
     * [CORREGIDO]
     * Ya no modificamos $this->rules aquí.
     */
    public function resetForm() {
        $this->reset(
            'classroom_code', 'name', 'building', 'capacity', 'has_projector', 
            'has_computers', 'computer_count', 'status', 'editingClassroom'
        );
        $this->resetValidation();
    }

    public function save() {
        // $this->validate() llamará automáticamente a nuestro método rules()
        $data = $this->validate(); 
        
        $model = $this->editingClassroom ?? new ClassroomResource();
        $model->fill($data);
        $model->save();
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Aula guardada correctamente.',
        ]);
    }

    public function confirmDelete(int $id) {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Aula?',
            'text' => 'Esta acción eliminará el aula. ¿Continuar?',
            'onConfirmed' => 'deleteClassroom'
        ]);
    }

    #[On('deleteClassroom')]
    public function deleteClassroom(int $id) {
        try {
            ClassroomResource::findOrFail($id)->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El aula ha sido eliminada.',
            ]);
        } catch (QueryException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se puede eliminar, es probable que esté asignada a un horario.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render() {
        $query = ClassroomResource::query();
        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('classroom_code', 'like', '%' . $this->search . '%');
        }
        $classrooms = $query->orderBy('created_at', 'desc')->paginate(10); 
        
        return view('livewire.pages.settings.classrooms.classroom-manager', [
            'classrooms' => $classrooms,
        ]);
    }
}