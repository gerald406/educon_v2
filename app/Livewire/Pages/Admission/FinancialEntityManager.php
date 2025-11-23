<?php

namespace App\Livewire\Pages\Admission;

use App\Models\FinancialEntity;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class FinancialEntityManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $name = '';
    public $code = ''; // Código interno o número de cuenta
    public $is_active = true;

    // --- PROPIEDADES DE ESTADO ---
    public ?FinancialEntity $editingEntity = null;
    public $isModalOpen = false;

    protected $rules = [
        'name' => 'required|string|max:100',
        'code' => 'nullable|string|max:20',
        'is_active' => 'boolean',
    ];

    // --- ACCIONES ---

    public function render()
    {
        $entities = FinancialEntity::orderBy('name')->paginate(10);

        return view('livewire.pages.admission.financial-entity-manager', [
            'entities' => $entities,
        ]);
    }

    public function create()
    {
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function edit(FinancialEntity $entity)
    {
        $this->editingEntity = $entity;
        $this->name = $entity->name;
        $this->code = $entity->code;
        $this->is_active = $entity->is_active;
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        FinancialEntity::updateOrCreate(
            ['id' => $this->editingEntity?->id],
            [
                'name' => $this->name,
                'code' => $this->code,
                'is_active' => $this->is_active,
            ]
        );

        $this->isModalOpen = false;
        $this->resetInput();

        // Opcional: Mostrar notificación
        // $this->dispatch('saved'); 
    }

    public function resetInput()
    {
        $this->reset('name', 'code', 'is_active', 'editingEntity');
    }
}
