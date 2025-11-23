<?php

namespace App\Livewire\Pages\Admission;

use App\Models\AdmissionModality;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdmissionModalityManager extends Component
{
    use WithPagination;

    public $name, $type = 'ordinario', $description, $is_active = true;
    public ?AdmissionModality $editingModality = null;
    public $isModalOpen = false;

    protected $rules = [
        'name' => 'required|string|max:150',
        'type' => 'required|in:ordinario,extraordinario',
        'description' => 'nullable|string',
        'is_active' => 'boolean'
    ];

    public function render()
    {
        return view('livewire.pages.admission.admission-modality-manager', [
            'modalities' => AdmissionModality::orderBy('type')->paginate(10)
        ]);
    }

    public function save()
    {
        $this->validate();

        AdmissionModality::updateOrCreate(
            ['id' => $this->editingModality?->id],
            [
                'name' => $this->name,
                'type' => $this->type,
                'description' => $this->description,
                'is_active' => $this->is_active
            ]
        );

        $this->isModalOpen = false;
        $this->resetInput();
    }

    public function edit(AdmissionModality $modality)
    {
        $this->editingModality = $modality;
        $this->name = $modality->name;
        $this->type = $modality->type;
        $this->description = $modality->description;
        $this->is_active = $modality->is_active;
        $this->isModalOpen = true;
    }

    public function create()
    {
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function resetInput()
    {
        $this->reset('name', 'type', 'description', 'is_active', 'editingModality');
    }
}
