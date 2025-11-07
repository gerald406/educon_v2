<?php

namespace App\Livewire\Pages\Settings\EvaluationTypes;

use App\Models\EvaluationType;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class EvaluationTypeManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $name = '';
    public $description = '';
    public $weight_percentage = 0.00;
    public $is_droppable = false; // ¿Se puede eliminar/anular esta nota?
    public $sort_order = 1;
    public $status = 'active';

    // --- PROPIEDADES DE ESTADO ---
    public ?EvaluationType $editingType = null;
    public $isModalOpen = false;
    public $search = '';

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'description' => 'nullable|string|max:255',
            'weight_percentage' => 'required|numeric|min:0|max:100',
            'is_droppable' => 'boolean',
            'sort_order' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('evaluation_types')->ignore($this->editingType?->id)
            ],
        ];
    }
    
    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(EvaluationType $type)
    {
        $this->editingType = $type;
        $this->fill($type->only(
            'name', 'description', 'weight_percentage', 
            'is_droppable', 'sort_order', 'status'
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
        $this->reset();
        $this->resetValidation();
    }

    public function save()
    {
        $data = $this->validate();
        
        $model = $this->editingType ?? new EvaluationType();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Tipo de evaluación guardado correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Tipo?',
            'text' => 'Esto eliminará el tipo de evaluación. Asegúrese de que no esté en uso.',
            'onConfirmed' => 'deleteType'
        ]);
    }

    #[On('deleteType')]
    public function deleteType(int $id)
    {
        try {
            EvaluationType::findOrFail($id)->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El tipo de evaluación ha sido eliminado.',
            ]);
        } catch (QueryException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se puede eliminar, es probable que esté asociado a calificaciones.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $query = EvaluationType::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        
        $types = $query->orderBy('sort_order')->paginate(10);

        return view('livewire.pages.settings.evaluation-types.evaluation-type-manager', [
            'types' => $types,
        ]);
    }
}