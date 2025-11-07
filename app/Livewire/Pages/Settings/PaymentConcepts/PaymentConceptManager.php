<?php

namespace App\Livewire\Pages\Settings\PaymentConcepts;

use App\Models\PaymentConcept;
use Illuminate\Database\QueryException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class PaymentConceptManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $code = '';
    public $tupa_code = '';
    public $description = '';
    public $amount = 0.00;
    public $concept_type = 'fee';
    public $is_taxable = false;
    public $tax_rate = 0.00;
    public $is_mandatory = false;
    public $status = 'active';

    // --- PROPIEDADES DE ESTADO ---
    public ?PaymentConcept $editingConcept = null;
    public $isModalOpen = false;
    public $search = '';

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        $rules = [
            'description' => 'required|string|max:200',
            'tupa_code' => 'nullable|string|max:20|unique:payment_concepts,tupa_code' . ($this->editingConcept ? ',' . $this->editingConcept->id : ''),
            'amount' => 'required|numeric|min:0',
            'concept_type' => 'required|in:enrollment,tuition,certificate,statement,fee,other',
            'is_taxable' => 'boolean',
            'tax_rate' => 'required_if:is_taxable,true|numeric|min:0|max:100',
            'is_mandatory' => 'boolean',
            'status' => 'required|in:active,inactive',
        ];

        // Regla dinámica para 'code' (SKU interno)
        if ($this->editingConcept) {
            $rules['code'] = 'required|string|max:20|unique:payment_concepts,code,' . $this->editingConcept->id;
        } else {
            $rules['code'] = 'required|string|max:20|unique:payment_concepts,code';
        }

        return $rules;
    }

    /**
     * Hook para resetear tax_rate si is_taxable se desmarca.
     */
    public function updatedIsTaxable($value)
    {
        if (!$value) {
            $this->tax_rate = 0.00;
        } else {
            $this->tax_rate = 18.00; // Asumimos 18% (IGV Perú) por defecto
        }
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(PaymentConcept $concept)
    {
        $this->editingConcept = $concept;
        
        $this->fill($concept->only(
            'code', 'tupa_code', 'description', 'amount', 'concept_type',
            'is_taxable', 'tax_rate', 'is_mandatory', 'status'
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
        $this->reset(
            'code', 'tupa_code', 'description', 'amount', 'concept_type',
            'is_taxable', 'tax_rate', 'is_mandatory', 'status', 'editingConcept'
        );
        $this->resetValidation();
    }

    public function save()
    {
        $data = $this->validate();
        
        $model = $this->editingConcept ?? new PaymentConcept();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Concepto de pago guardado correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Concepto?',
            'text' => 'Esta acción eliminará el concepto de pago. ¿Continuar?',
            'onConfirmed' => 'deleteConcept'
        ]);
    }

    #[On('deleteConcept')]
    public function deleteConcept(int $id)
    {
        try {
            PaymentConcept::findOrFail($id)->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El concepto ha sido eliminado.',
            ]);
        } catch (QueryException $e) {
            // Manejo de error si el concepto está siendo usado (llave foránea)
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se puede eliminar, es probable que esté asignado a un pago.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $query = PaymentConcept::query();
        if ($this->search) {
            $query->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  ->orWhere('tupa_code', 'like', '%' . $this->search . '%');
        }
        $concepts = $query->orderBy('description')->paginate(10);

        return view('livewire.pages.settings.payment-concepts.payment-concept-manager', [
            'concepts' => $concepts,
        ]);
    }
}