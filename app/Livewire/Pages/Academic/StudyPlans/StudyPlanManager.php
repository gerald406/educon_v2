<?php

namespace App\Livewire\Pages\Academic\StudyPlans;

use App\Models\Career;
use App\Models\StudyPlan;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule; // Importante para reglas 'unique' complejas
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StudyPlanManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $career_id = '';
    public $code = '';
    public $name = '';
    public $version = '';
    public $start_date = '';
    public $end_date = null;
    public $total_credits = 0;
    public $total_hours = 0;
    public $status = 'active';

    // --- PROPIEDADES DE ESTADO ---
    public ?StudyPlan $editingStudyPlan = null;
    public $isModalOpen = false;
    public $search = '';

    // Colección para el dropdown de carreras
    public Collection $careers;

    /**
     * Hook 'mount': Carga las carreras.
     */
    public function mount()
    {
        $this->careers = Career::where('status', 'active')->pluck('name', 'id');
        // Asignar la primera carrera por defecto
        if (!$this->editingStudyPlan && $this->careers->count() > 0) {
            $this->career_id = $this->careers->keys()->first();
        }
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'career_id' => 'required|exists:careers,id',
            'name' => 'required|string|max:100',
            'version' => 'required|string|max:10',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'total_credits' => 'required|integer|min:1',
            'total_hours' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive,obsolete',
            // Regla avanzada: El 'code' debe ser único para la 'career_id' seleccionada.
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('study_plans')
                    ->where(fn ($query) => $query->where('career_id', $this->career_id))
                    ->ignore($this->editingStudyPlan?->id)
            ],
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(StudyPlan $studyPlan)
    {
        $this->editingStudyPlan = $studyPlan;
        $this->fill($studyPlan->only(
            'career_id', 'code', 'name', 'version', 'total_credits', 'total_hours', 'status'
        ));
        // Formatear fechas para los inputs type="date"
        $this->start_date = $studyPlan->start_date->format('Y-m-d');
        $this->end_date = $studyPlan->end_date?->format('Y-m-d');
        
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->resetExcept('careers'); // No borramos la colección de carreras
        $this->resetValidation();
        // Re-asignar la carrera por defecto
        if ($this->careers->count() > 0) {
            $this->career_id = $this->careers->keys()->first();
        }
    }

    public function save()
    {
        $data = $this->validate();
        
        $model = $this->editingStudyPlan ?? new StudyPlan();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Plan de Estudio guardado correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Plan de Estudio?',
            'text' => 'Esto eliminará el plan y todos sus módulos y cursos asociados.',
            'onConfirmed' => 'deleteStudyPlan'
        ]);
    }

    #[On('deleteStudyPlan')]
    public function deleteStudyPlan(int $id)
    {
        try {
            StudyPlan::findOrFail($id)->delete(); // El 'onDelete('cascade')' de la migración borrará los módulos/cursos
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El plan de estudio ha sido eliminado.',
            ]);
        } catch (QueryException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se pudo eliminar el plan. Verifique las dependencias.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $query = StudyPlan::with('career'); // Carga ansiosa de la relación 'career'

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%')
                  // Permite buscar por el nombre de la carrera
                  ->orWhereHas('career', function ($subQuery) {
                      $subQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }
        
        $plans = $query->orderBy('name')->paginate(10);

        return view('livewire.pages.academic.study-plans.study-plan-manager', [
            'plans' => $plans,
        ]);
    }
}