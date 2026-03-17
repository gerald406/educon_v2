<?php

namespace App\Livewire\Pages\Academic\Modules;

use App\Models\Career;
use App\Models\Module;
use App\Models\StudyPlan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ModuleManager extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    // --- Formulario ---
    public $study_plan_id = '';
    public $module_number = 1;
    public $name = '';
    public $description = '';
    public $minimum_credits_approval = 20;
    public $total_hours = 0;
    public $sort_order = 1;
    public $status = 'active';

    // --- Filtros Auxiliares (Estado UI) ---
    public $selectedCareerId = ''; // Para filtrar el select de planes

    // --- Estado ---
    public ?Module $editingModule = null;
    public $isModalOpen = false;
    public $search = '';

    // --- Colecciones ---
    public Collection $careers;
    public Collection $availablePlans; // Se llena dinámicamente

    public function mount()
    {
        $this->careers = Career::where('status', 'active')->orderBy('name')->pluck('name', 'id');
        $this->availablePlans = collect();
    }

    public function rules()
    {
        return [
            'study_plan_id' => 'required|exists:study_plans,id',
            'module_number' => [
                'required',
                'integer',
                'min:1',
                // Único número dentro del plan
                Rule::unique('modules')->where('study_plan_id', $this->study_plan_id)->ignore($this->editingModule?->id)
            ],
            'name' => 'required|string|max:150',
            'minimum_credits_approval' => 'required|integer|min:0',
            'total_hours' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }

    // --- Reactividad ---
    public function updatedSelectedCareerId($value)
    {
        // Al cambiar carrera, reseteamos plan y cargamos los nuevos
        $this->study_plan_id = '';
        $this->availablePlans = $value
            ? StudyPlan::where('career_id', $value)->where('status', 'active')->get()
            : collect();
    }

    // --- Acciones ---
    public function create()
    {
        $this->authorize('gestionar-estructura-academica');
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function edit(Module $module)
    {
        $this->authorize('gestionar-estructura-academica');
        $this->editingModule = $module;

        // Reconstruir el estado de los selects
        $plan = $module->studyPlan;
        $this->selectedCareerId = $plan->career_id;
        $this->updatedSelectedCareerId($plan->career_id); // Cargar planes manualmente

        $this->study_plan_id = $module->study_plan_id;
        $this->module_number = $module->module_number;
        $this->name = $module->name;
        $this->description = $module->description;
        $this->minimum_credits_approval = $module->minimum_credits_approval;
        $this->total_hours = $module->total_hours;
        $this->sort_order = $module->sort_order;
        $this->status = $module->status;

        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->authorize('gestionar-estructura-academica');
        $validated = $this->validate();

        try {
            if ($this->editingModule) {
                $this->editingModule->update($validated);
                $msg = 'Módulo actualizado.';
            } else {
                Module::create($validated);
                $msg = 'Módulo creado.';
            }
            $this->isModalOpen = false;
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Éxito', 'text' => $msg]);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function confirmDelete($id)
    {
        $this->authorize('gestionar-estructura-academica');
        $this->dispatch('swal:confirm', [
            'title' => '¿Eliminar Módulo?',
            'text' => 'Se eliminarán todos los cursos asociados.',
            'id' => $id,
            'method' => 'deleteModule'
        ]);
    }

    #[On('deleteModule')]
    public function deleteModule($id)
    {
        try {
            Module::findOrFail($id)->delete();
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Módulo eliminado.']);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se puede eliminar.']);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInput()
    {
        $this->editingModule = null;
        $this->selectedCareerId = '';
        $this->availablePlans = collect();
        $this->study_plan_id = '';
        $this->module_number = 1;
        $this->name = '';
        $this->status = 'active';
    }

    public function render()
    {
        $query = Module::with(['studyPlan.career'])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhereHas('studyPlan', fn($sq) => $sq->where('name', 'like', "%{$this->search}%"));
            });

        return view('livewire.pages.academic.modules.module-manager', [
            'modules' => $query->orderBy('study_plan_id')->orderBy('sort_order')->paginate(10)
        ]);
    }
}
