<?php

namespace App\Livewire\Pages\Academic\DidacticUnits;

use App\Models\Career;
use App\Models\DidacticUnit;
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
class DidacticUnitManager extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    // --- Formulario ---
    public $module_id = '';
    public $code = '';
    public $name = '';
    public $semester = 1;
    public $weekly_hours = 0;
    public $total_hours = 0;
    public $credits = 0;
    public $unit_type = 'career';
    public $semester_order = 1;
    public $status = 'active';

    // --- Filtros Cascada ---
    public $selectedCareerId = '';
    public $selectedStudyPlanId = '';

    // --- Estado ---
    public ?DidacticUnit $editingUnit = null;
    public $isModalOpen = false;
    public $search = '';

    // --- Colecciones ---
    public Collection $careers;
    public Collection $studyPlans;
    public Collection $modules;

    public function mount()
    {
        $this->careers = Career::where('status', 'active')->orderBy('name')->pluck('name', 'id');
        $this->studyPlans = collect();
        $this->modules = collect();
    }

    public function rules()
    {
        return [
            'module_id' => 'required|exists:modules,id',
            'code' => ['required', 'max:20', Rule::unique('didactic_units')->where('module_id', $this->module_id)->ignore($this->editingUnit?->id)],
            'name' => 'required|string|max:200',
            'semester' => 'required|integer|min:1|max:8',
            'weekly_hours' => 'required|integer|min:1',
            'total_hours' => 'required|integer|min:1',
            'credits' => 'required|integer|min:1',
            'unit_type' => 'required|in:career,transversal',
            'semester_order' => 'required|integer',
            'status' => 'required|in:active,inactive',
        ];
    }

    // --- Reactividad Cascada ---
    public function updatedSelectedCareerId($value)
    {
        $this->selectedStudyPlanId = '';
        $this->module_id = '';
        $this->modules = collect();
        $this->studyPlans = $value ? StudyPlan::where('career_id', $value)->where('status', 'active')->get() : collect();
    }

    public function updatedSelectedStudyPlanId($value)
    {
        $this->module_id = '';
        $this->modules = $value ? Module::where('study_plan_id', $value)->orderBy('module_number')->get() : collect();
    }

    // --- Acciones ---
    public function create()
    {
        $this->authorize('gestionar-estructura-academica');
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function edit(DidacticUnit $unit)
    {
        $this->authorize('gestionar-estructura-academica');
        $this->editingUnit = $unit;

        // Reconstruir cascada inversa
        $module = $unit->module;
        $plan = $module->studyPlan;

        $this->selectedCareerId = $plan->career_id;
        $this->updatedSelectedCareerId($plan->career_id);

        $this->selectedStudyPlanId = $plan->id;
        $this->updatedSelectedStudyPlanId($plan->id);

        $this->module_id = $unit->module_id;
        // Rellenar datos
        $this->code = $unit->code;
        $this->name = $unit->name;
        $this->semester = $unit->semester;
        $this->weekly_hours = $unit->weekly_hours;
        $this->total_hours = $unit->total_hours;
        $this->credits = $unit->credits;
        $this->unit_type = $unit->unit_type;
        $this->semester_order = $unit->semester_order;
        $this->status = $unit->status;

        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->authorize('gestionar-estructura-academica');
        $validated = $this->validate();

        try {
            if ($this->editingUnit) {
                $this->editingUnit->update($validated);
                $msg = 'Unidad actualizada.';
            } else {
                DidacticUnit::create($validated);
                $msg = 'Unidad creada.';
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
        $this->dispatch('swal:confirm', ['title' => '¿Eliminar Curso?', 'text' => 'Cuidado si ya tiene notas registradas.', 'id' => $id, 'method' => 'deleteUnit']);
    }

    #[On('deleteUnit')]
    public function deleteUnit($id)
    {
        try {
            DidacticUnit::findOrFail($id)->delete();
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Curso eliminado.']);
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
        $this->editingUnit = null;
        // No reseteamos los filtros principales para facilitar creación masiva
        $this->code = '';
        $this->name = '';
        $this->semester = 1;
        $this->status = 'active';
    }

    public function render()
    {
        $query = DidacticUnit::with(['module.studyPlan.career'])
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")->orWhere('code', 'like', "%{$this->search}%");
            });

        return view('livewire.pages.academic.didactic-units.didactic-unit-manager', [
            'units' => $query->orderBy('module_id')->orderBy('semester_order')->paginate(10)
        ]);
    }
}
