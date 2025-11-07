<?php

namespace App\Livewire\Pages\Academic\Prerequisites;

use App\Models\Career;
use App\Models\DidacticUnit;
use App\Models\Module;
use App\Models\StudyPlan;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class PrerequisiteManager extends Component
{
    // --- COLECCIONES PARA FILTROS ---
    public Collection $careers;
    public Collection $studyPlans;
    public Collection $modules;
    public Collection $units; // Unidades del módulo seleccionado

    // --- FILTROS SELECCIONADOS (CURSO PRINCIPAL) ---
    public $selectedCareerId = '';
    public $selectedStudyPlanId = '';
    public $selectedModuleId = '';
    public $selectedUnitId = ''; // El ID del curso que VAMOS a modificar

    // --- CURSO PRINCIPAL Y SUS PRERREQUISITOS ---
    public ?DidacticUnit $mainUnit = null;
    public Collection $currentPrerequisites;

    // --- FORMULARIO PARA AÑADIR NUEVO PRERREQUISITO ---
    public Collection $availableUnitsToAdd; // Todas las unidades del plan
    public $unitToAddId = ''; // El ID del curso que SERÁ un prerrequisito

    /**
     * Hook 'mount': Carga las carreras.
     */
    public function mount()
    {
        $this->careers = Career::where('status', 'active')->pluck('name', 'id');
        $this->studyPlans = collect();
        $this->modules = collect();
        $this->units = collect();
        $this->currentPrerequisites = collect();
        $this->availableUnitsToAdd = collect();

        if ($this->careers->count() > 0) {
            $this->selectedCareerId = $this->careers->keys()->first();
            $this->updatedSelectedCareerId($this->selectedCareerId);
        }
    }

    // --- LÓGICA DE FILTROS DEPENDIENTES ---
    
    public function updatedSelectedCareerId($value)
    {
        $this->studyPlans = StudyPlan::where('career_id', $value)
            ->where('status', 'active')->pluck('name', 'id');
        $this->selectedStudyPlanId = $this->studyPlans->keys()->first() ?? '';
        $this->updatedSelectedStudyPlanId($this->selectedStudyPlanId);
    }

    public function updatedSelectedStudyPlanId($value)
    {
        $this->modules = Module::where('study_plan_id', $value)
            ->where('status', 'active')->pluck('name', 'id');
        $this->selectedModuleId = $this->modules->keys()->first() ?? '';
        $this->updatedSelectedModuleId($this->selectedModuleId);
    }

    public function updatedSelectedModuleId($value)
    {
        $this->units = DidacticUnit::where('module_id', $value)
            ->where('status', 'active')->pluck('name', 'id');
        $this->selectedUnitId = $this->units->keys()->first() ?? '';
        $this->updatedSelectedUnitId($this->selectedUnitId); // Cargar el curso
    }

    /**
     * Hook: Se ejecuta cuando se selecciona el curso principal.
     */
    public function updatedSelectedUnitId($value)
    {
        if (empty($value)) {
            $this->mainUnit = null;
            $this->currentPrerequisites = collect();
            $this->availableUnitsToAdd = collect();
            return;
        }

        // 1. Cargar el curso principal y sus prerrequisitos actuales
        $this->mainUnit = DidacticUnit::with('prerequisites')->find($value);
        $this->currentPrerequisites = $this->mainUnit->prerequisites;

        // 2. Cargar la lista de cursos que se pueden añadir
        $currentPlanId = $this->mainUnit->module->study_plan_id;
        
        // Cursos del mismo plan
        $allUnitsInPlan = DidacticUnit::whereHas('module', function ($query) use ($currentPlanId) {
            $query->where('study_plan_id', $currentPlanId);
        })->get();
        
        // Excluir el curso actual y los que ya son prerrequisitos
        $this->availableUnitsToAdd = $allUnitsInPlan
            ->where('id', '!=', $this->mainUnit->id) // No puede ser prerrequisito de sí mismo
            ->whereNotIn('id', $this->currentPrerequisites->pluck('id')); // No mostrar los que ya están añadidos

        $this->unitToAddId = '';
    }

    // --- ACCIONES DE GESTIÓN ---

    /**
     * Añade el curso seleccionado como prerrequisito.
     */
    public function addPrerequisite()
    {
        $this->validate(['unitToAddId' => 'required|exists:didactic_units,id']);

        if ($this->mainUnit) {
            // Usamos 'syncWithoutDetaching' para evitar duplicados
            $this->mainUnit->prerequisites()->syncWithoutDetaching($this->unitToAddId);
            
            // Recargamos la información
            $this->updatedSelectedUnitId($this->mainUnit->id);
            $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Hecho!', 'text' => 'Prerrequisito añadido.']);
        }
    }

    /**
     * Quita un prerrequisito de la lista.
     */
    public function removePrerequisite(int $prerequisiteId)
    {
        if ($this->mainUnit) {
            $this->mainUnit->prerequisites()->detach($prerequisiteId);
            
            // Recargamos la información
            $this->updatedSelectedUnitId($this->mainUnit->id);
            $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Hecho!', 'text' => 'Prerrequisito eliminado.']);
        }
    }

    // --- RENDER ---
    public function render()
    {
        return view('livewire.pages.academic.prerequisites.prerequisite-manager');
    }
}