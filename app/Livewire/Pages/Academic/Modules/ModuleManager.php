<?php

namespace App\Livewire\Pages\Academic\Modules;

use App\Models\Career;
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
class ModuleManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $study_plan_id = '';
    public $module_number = 1;
    public $name = '';
    public $minimum_credits_approval = 20;
    public $total_hours = 0;
    public $sort_order = 1;
    public $status = 'active';

    // --- PROPIEDADES DE ESTADO ---
    public ?Module $editingModule = null;
    public $isModalOpen = false;
    public $search = '';

    // --- PROPIEDADES PARA DROPDOWNS DEPENDIENTES ---
    public Collection $careers;         // Todas las carreras
    public Collection $allStudyPlans;   // Todos los planes de estudio
    public Collection $availableStudyPlans; // Planes filtrados por carrera

    // Propiedad para el filtro del modal
    public $selectedCareerId = '';

    /**
     * Hook 'mount': Carga los datos para los dropdowns.
     */
    public function mount()
    {
        $this->careers = Career::where('status', 'active')->pluck('name', 'id');
        $this->allStudyPlans = StudyPlan::where('status', 'active')->get(['id', 'name', 'career_id']);
        $this->availableStudyPlans = collect(); // Inicia vacío

        // Si hay carreras, selecciona la primera por defecto
        if ($this->careers->count() > 0) {
            $this->selectedCareerId = $this->careers->keys()->first();
            $this->updateAvailableStudyPlans(); // Filtra los planes para esa carrera
        }
    }

    /**
     * Hook: Se ejecuta cuando la propiedad 'selectedCareerId' cambia.
     */
    public function updatedSelectedCareerId($value)
    {
        $this->updateAvailableStudyPlans();
        // Resetea el plan seleccionado si la carrera cambia
        $this->study_plan_id = '';
    }

    /**
     * Lógica para filtrar los planes de estudio.
     */
    public function updateAvailableStudyPlans()
    {
        $this->availableStudyPlans = $this->allStudyPlans
            ->where('career_id', $this->selectedCareerId);
        
        // Si solo hay un plan disponible, seleccionarlo automáticamente
        if ($this->availableStudyPlans->count() === 1) {
            $this->study_plan_id = $this->availableStudyPlans->first()->id;
        }
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'study_plan_id' => 'required|exists:study_plans,id',
            'name' => 'required|string|max:150',
            'minimum_credits_approval' => 'required|integer|min:1',
            'total_hours' => 'required|integer|min:1',
            'sort_order' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            // Regla: El 'module_number' debe ser único para el 'study_plan_id'
            'module_number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('modules')
                    ->where(fn ($query) => $query->where('study_plan_id', $this->study_plan_id))
                    ->ignore($this->editingModule?->id)
            ],
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(Module $module)
    {
        $this->editingModule = $module;
        $this->fill($module->only(
            'study_plan_id', 'module_number', 'name', 'minimum_credits_approval',
            'total_hours', 'sort_order', 'status'
        ));

        // Cargar y seleccionar la carrera correcta en el dropdown
        $this->selectedCareerId = $module->studyPlan->career_id;
        $this->updateAvailableStudyPlans(); // Cargar los planes de esa carrera
        $this->study_plan_id = $module->study_plan_id; // Asegurarse de que esté seleccionado

        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->resetExcept('careers', 'allStudyPlans', 'availableStudyPlans', 'selectedCareerId');
        $this->resetValidation();
        // Re-seleccionar la primera carrera y sus planes
        if ($this->careers->count() > 0) {
            $this->selectedCareerId = $this->careers->keys()->first();
            $this->updateAvailableStudyPlans();
        }
    }

    public function save()
    {
        $data = $this->validate();
        
        $model = $this->editingModule ?? new Module();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Módulo guardado correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Módulo?',
            'text' => 'Esto eliminará el módulo y todos sus cursos (unidades) asociados.',
            'onConfirmed' => 'deleteModule'
        ]);
    }

    #[On('deleteModule')]
    public function deleteModule(int $id)
    {
        try {
            Module::findOrFail($id)->delete(); // 'onDelete('cascade')' borrará las unidades
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El módulo ha sido eliminado.',
            ]);
        } catch (QueryException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se pudo eliminar el módulo. Verifique dependencias.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $query = Module::with(['studyPlan.career']); // Carga anidada: Módulo -> Plan -> Carrera

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhereHas('studyPlan', function ($subQuery) {
                      $subQuery->where('name', 'like', '%' . $this->search . '%')
                               ->orWhereHas('career', function ($subSubQuery) {
                                   $subSubQuery->where('name', 'like', '%' . $this->search . '%');
                               });
                  });
            });
        }
        
        $modules = $query->orderBy('sort_order')->paginate(10);

        return view('livewire.pages.academic.modules.module-manager', [
            'modules' => $modules,
        ]);
    }
}