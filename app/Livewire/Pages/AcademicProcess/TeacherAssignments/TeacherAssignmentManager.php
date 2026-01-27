<?php

namespace App\Livewire\Pages\AcademicProcess\TeacherAssignments;

use App\Models\AcademicPeriod;
use App\Models\Career;
use App\Models\DidacticUnit;
use App\Models\Module;
use App\Models\Shift;
use App\Models\StudyPlan;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TeacherAssignmentManager extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    // --- Filtros Globales (Dashboard) ---
    public $filterPeriodId = '';
    public $filterCareerId = '';

    // --- Formulario ---
    public $academic_period_id = '';
    public $teacher_id = '';
    public $didactic_unit_id = '';
    public $shift_id = '';
    public $section = 'A';
    public $max_capacity = 30;
    public $status = 'active';

    // --- Selects Cascada del Modal ---
    public $selectedCareerId = '';
    public $selectedStudyPlanId = '';
    public $selectedModuleId = '';

    // --- Colecciones ---
    public Collection $periods;
    public Collection $careers;
    public Collection $teachers;
    public Collection $shifts;

    // Colecciones Dinámicas
    public Collection $studyPlans;
    public Collection $modules;
    public Collection $units;

    // --- Estado ---
    public ?TeacherAssignment $editingAssignment = null;
    public $isModalOpen = false;
    public $search = '';

    public function mount()
    {
        // Carga inicial
        $this->periods = AcademicPeriod::orderBy('start_date', 'desc')->get();

        // Seleccionar periodo activo por defecto
        $activePeriod = $this->periods->where('status', 'active')->first();
        $this->filterPeriodId = $activePeriod ? $activePeriod->id : ($this->periods->first()->id ?? '');
        $this->academic_period_id = $this->filterPeriodId;

        $this->careers = Career::where('status', 'active')->get();
        $this->teachers = Teacher::with('user')->where('status', 'active')->get();
        $this->shifts = Shift::where('status', 'active')->get();

        // Inicializar colecciones vacías
        $this->studyPlans = collect();
        $this->modules = collect();
        $this->units = collect();
    }

    // --- LOGICA CASCADA (Selects) ---

    public function updatedSelectedCareerId($value)
    {
        $this->selectedStudyPlanId = '';
        $this->selectedModuleId = '';
        $this->didactic_unit_id = '';
        $this->studyPlans = $value ? StudyPlan::where('career_id', $value)->where('status', 'active')->get() : collect();
        $this->modules = collect();
        $this->units = collect();
    }

    public function updatedSelectedStudyPlanId($value)
    {
        $this->selectedModuleId = '';
        $this->didactic_unit_id = '';
        $this->modules = $value ? Module::where('study_plan_id', $value)->orderBy('module_number')->get() : collect();
        $this->units = collect();
    }

    public function updatedSelectedModuleId($value)
    {
        $this->didactic_unit_id = '';
        $this->units = $value ? DidacticUnit::where('module_id', $value)->orderBy('semester')->get() : collect();
    }

    // --- CRUD ---

    public function rules()
    {
        return [
            'academic_period_id' => 'required|exists:academic_periods,id',
            'teacher_id' => 'required|exists:teachers,id',
            'didactic_unit_id' => 'required|exists:didactic_units,id',
            'shift_id' => 'required|exists:shifts,id',
            'section' => 'required|string|max:5',
            'max_capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,suspended,completed',

            // Regla compuesta: No duplicar (Docente + Curso + Periodo + Sección)
            // Se puede relajar si un docente dicta a dos grupos, pero la sección debe cambiar (A vs B)
        ];
    }

    public function create()
    {
        $this->authorize('gestionar-carga-academica');
        $this->resetInput();
        // Mantener el periodo filtrado
        $this->academic_period_id = $this->filterPeriodId;
        $this->isModalOpen = true;
    }

    public function edit(TeacherAssignment $assignment)
    {
        $this->authorize('gestionar-carga-academica');
        $this->editingAssignment = $assignment;

        // Reconstruir la cascada inversa para que los selects se llenen
        $unit = $assignment->didacticUnit;
        $module = $unit->module;
        $plan = $module->studyPlan;

        $this->selectedCareerId = $plan->career_id;
        $this->updatedSelectedCareerId($plan->career_id); // Carga planes

        $this->selectedStudyPlanId = $plan->id;
        $this->updatedSelectedStudyPlanId($plan->id); // Carga módulos

        $this->selectedModuleId = $unit->module_id;
        $this->updatedSelectedModuleId($unit->module_id); // Carga cursos

        // Datos directos
        $this->academic_period_id = $assignment->academic_period_id;
        $this->teacher_id = $assignment->teacher_id;
        $this->didactic_unit_id = $assignment->didactic_unit_id;
        $this->shift_id = $assignment->shift_id;
        $this->section = $assignment->section;
        $this->max_capacity = $assignment->max_capacity;
        $this->status = $assignment->status;

        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->authorize('gestionar-carga-academica');
        $validated = $this->validate();

        // Validar duplicidad manualmente para mayor control
        $exists = TeacherAssignment::where('academic_period_id', $this->academic_period_id)
            ->where('didactic_unit_id', $this->didactic_unit_id)
            ->where('section', $this->section)
            ->where('id', '!=', $this->editingAssignment?->id)
            ->exists();

        if ($exists) {
            $this->addError('section', 'Ya existe una sección ' . $this->section . ' para este curso en este periodo.');
            return;
        }

        if ($this->editingAssignment) {
            $this->editingAssignment->update($validated);
            $msg = 'Carga actualizada.';
        } else {
            TeacherAssignment::create($validated);
            $msg = 'Carga asignada correctamente.';
        }

        $this->isModalOpen = false;
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Éxito', 'text' => $msg]);
    }

    public function confirmDelete($id)
    {
        $this->authorize('gestionar-carga-academica');
        $this->dispatch('swal:confirm', [
            'title' => '¿Eliminar Asignación?',
            'text' => 'Cuidado: Si hay alumnos matriculados, se perderá esa información.',
            'id' => $id,
            'method' => 'deleteAssignment'
        ]);
    }

    #[On('deleteAssignment')]
    public function deleteAssignment($id)
    {
        try {
            TeacherAssignment::findOrFail($id)->delete();
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Asignación eliminada.']);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo eliminar. Verifique alumnos matriculados.']);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInput()
    {
        $this->editingAssignment = null;
        $this->selectedCareerId = '';
        $this->selectedStudyPlanId = '';
        $this->selectedModuleId = '';
        $this->didactic_unit_id = '';
        $this->teacher_id = '';
        $this->shift_id = '';
        $this->section = 'A';
        $this->max_capacity = 30;
        $this->status = 'active';
        $this->studyPlans = collect();
        $this->modules = collect();
        $this->units = collect();
    }

    public function render()
    {
        // Filtro Principal
        $query = TeacherAssignment::with(['teacher.user', 'didacticUnit', 'shift'])
            ->where('academic_period_id', $this->filterPeriodId);

        // Filtro Búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('didacticUnit', fn($sq) => $sq->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('teacher.user', fn($sq) => $sq->where('name', 'like', "%{$this->search}%")->orWhere('lastname', 'like', "%{$this->search}%"));
            });
        }

        // Filtro Carrera (Opcional en la tabla)
        if ($this->filterCareerId) {
            $query->whereHas('didacticUnit.module.studyPlan', fn($q) => $q->where('career_id', $this->filterCareerId));
        }

        return view('livewire.pages.academic-process.teacher-assignments.teacher-assignment-manager', [
            'assignments' => $query->orderBy('didactic_unit_id')->paginate(10)
        ]);
    }
}
