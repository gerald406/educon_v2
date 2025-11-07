<?php

namespace App\Livewire\Pages\Academic\DidacticUnits;

use App\Models\Career;
use App\Models\DidacticUnit;
use App\Models\Module;
use App\Models\StudyPlan;
use Illuminate\Database\QueryException;
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

    // --- PROPIEDADES DEL FORMULARIO ---
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

    // --- PROPIEDADES DE ESTADO ---
    public ?DidacticUnit $editingUnit = null;
    public $isModalOpen = false;
    public $search = '';

    // --- PROPIEDADES PARA FILTROS DEPENDIENTES ---
    public Collection $careers;
    public Collection $studyPlans;
    public Collection $modules;

    public $selectedCareerId = '';
    public $selectedStudyPlanId = '';
    public $selectedModuleId = ''; // Este es el 'module_id' principal

    /**
     * Hook 'mount': Carga los datos para los filtros.
     */
    public function mount()
    {
        $this->careers = Career::where('status', 'active')->pluck('name', 'id');
        $this->studyPlans = collect();
        $this->modules = collect();

        // Si hay carreras, selecciona la primera por defecto
        if ($this->careers->count() > 0) {
            $this->selectedCareerId = $this->careers->keys()->first();
            $this->updatedSelectedCareerId($this->selectedCareerId);
        }
    }

    // --- LÓGICA DE FILTROS DEPENDIENTES ---

    /**
     * Hook: Se ejecuta cuando la 'selectedCareerId' cambia.
     */
    public function updatedSelectedCareerId($value)
    {
        $this->studyPlans = StudyPlan::where('career_id', $value)
            ->where('status', 'active')
            ->pluck('name', 'id');
        $this->selectedStudyPlanId = $this->studyPlans->keys()->first() ?? '';
        $this->updatedSelectedStudyPlanId($this->selectedStudyPlanId);
    }

    /**
     * Hook: Se ejecuta cuando la 'selectedStudyPlanId' cambia.
     */
    public function updatedSelectedStudyPlanId($value)
    {
        $this->modules = Module::where('study_plan_id', $value)
            ->where('status', 'active')
            ->pluck('name', 'id');
        $this->selectedModuleId = $this->modules->keys()->first() ?? '';
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'name' => 'required|string|max:200',
            'semester' => 'required|integer|min:1|max:12',
            'weekly_hours' => 'required|integer|min:0',
            'total_hours' => 'required|integer|min:1',
            'credits' => 'required|integer|min:0',
            'unit_type' => 'required|in:career,transversal',
            'semester_order' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            // Regla: El 'code' debe ser único para el 'module_id'
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('didactic_units')
                    ->where(fn ($query) => $query->where('module_id', $this->selectedModuleId))
                    ->ignore($this->editingUnit?->id)
            ],
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(DidacticUnit $unit)
    {
        $this->editingUnit = $unit;
        $this->fill($unit->only(
            'code', 'name', 'semester', 'weekly_hours', 'total_hours',
            'credits', 'unit_type', 'semester_order', 'status'
        ));
        // El module_id ya está en $this->selectedModuleId
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset('code', 'name', 'semester', 'weekly_hours', 'total_hours',
                    'credits', 'unit_type', 'semester_order', 'status', 'editingUnit');
        $this->resetValidation();
    }

    public function save()
    {
        // Asegurarse de que el module_id está seteado
        $this->module_id = $this->selectedModuleId;
        
        $data = $this->validate(array_merge($this->rules(), [
            'module_id' => 'required|exists:modules,id'
        ]));
        
        $model = $this->editingUnit ?? new DidacticUnit();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Unidad Didáctica guardada correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Unidad?',
            'text' => 'Esto eliminará el curso (unidad didáctica).',
            'onConfirmed' => 'deleteUnit'
        ]);
    }

    #[On('deleteUnit')]
    public function deleteUnit(int $id)
    {
        try {
            DidacticUnit::findOrFail($id)->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'La unidad ha sido eliminada.',
            ]);
        } catch (QueryException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se puede eliminar, puede tener prerrequisitos o estar en uso.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $units = DidacticUnit::query()
            ->where('module_id', $this->selectedModuleId) // Filtra por el módulo seleccionado
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('semester')
            ->orderBy('semester_order')
            ->paginate(10); // Paginar los resultados

        return view('livewire.pages.academic.didactic-units.didactic-unit-manager', [
            'units' => $units,
        ]);
    }
}