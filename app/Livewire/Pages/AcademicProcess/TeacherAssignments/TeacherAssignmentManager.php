<?php

namespace App\Livewire\Pages\AcademicProcess\TeacherAssignments;

use App\Models\AcademicPeriod;
use App\Models\Career;
use App\Models\DidacticUnit;
use App\Models\Shift;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TeacherAssignmentManager extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    // --- Filtros Globales (Tabla) ---
    public $filterPeriodId = '';
    public $filterCareerId = '';
    public $search = '';

    // --- Formulario ---
    public $academic_period_id = '';
    public $teacher_id = '';
    public $didactic_unit_id = '';
    public $shift_id = '';
    public $section = 'A';
    public $max_capacity = 45; // [REQUERIMIENTO] Default 45
    public $status = 'active'; // [REQUERIMIENTO] Default Active

    // --- Buscadores Predictivos ---
    public $selectedCareerId = ''; // Filtro previo para cursos

    // Buscador Curso
    public $searchUnit = '';
    public $unitResults = [];
    public $selectedUnitName = ''; // Para mostrar en el input

    // Buscador Docente
    public $searchTeacher = '';
    public $teacherResults = [];
    public $selectedTeacherName = ''; // Para mostrar en el input

    // --- Colecciones Estáticas ---
    public Collection $periods;
    public Collection $careers;
    public Collection $shifts;

    // --- Estado ---
    public ?TeacherAssignment $editingAssignment = null;
    public $isModalOpen = false;

    public function mount()
    {
        $this->periods = AcademicPeriod::orderBy('start_date', 'desc')->get();

        // Periodo activo por defecto
        $activePeriod = $this->periods->where('status', 'active')->first();
        $this->filterPeriodId = $activePeriod ? $activePeriod->id : ($this->periods->first()->id ?? '');
        $this->academic_period_id = $this->filterPeriodId;

        $this->careers = Career::where('status', 'active')->get();
        $this->shifts = Shift::where('status', 'active')->get();
    }

    // ==========================================
    // LÓGICA DE BUSCADORES
    // ==========================================

    // 1. Buscador de Cursos (Filtrado por Carrera)
    public function updatedSelectedCareerId()
    {
        $this->resetUnitSearch(); // Limpiar curso si cambia la carrera
    }

    public function updatedSearchUnit($value)
    {
        if (strlen($value) < 2) {
            $this->unitResults = [];
            return;
        }

        // Buscar unidades didácticas que coincidan con el nombre
        // Y opcionalmente que pertenezcan a la carrera seleccionada
        $query = DidacticUnit::with(['module.studyPlan'])
            ->where('name', 'like', '%' . $value . '%');

        if ($this->selectedCareerId) {
            $query->whereHas('module.studyPlan', function ($q) {
                $q->where('career_id', $this->selectedCareerId);
            });
        }

        $this->unitResults = $query->take(10)->get();
    }

    public function selectUnit($id, $name, $module, $plan)
    {
        $this->didactic_unit_id = $id;
        $this->searchUnit = $name; // Texto visual
        $this->selectedUnitName = "$name ($module - $plan)"; // Detalle visual (opcional)
        $this->unitResults = [];
    }

    private function resetUnitSearch()
    {
        $this->didactic_unit_id = '';
        $this->searchUnit = '';
        $this->selectedUnitName = '';
        $this->unitResults = [];
    }

    // 2. Buscador de Docentes
    public function updatedSearchTeacher($value)
    {
        if (strlen($value) < 2) {
            $this->teacherResults = [];
            return;
        }

        $this->teacherResults = Teacher::with('user')
            ->whereHas('user', function ($q) use ($value) {
                $q->where('name', 'like', '%' . $value . '%')
                    ->orWhere('lastname', 'like', '%' . $value . '%')
                    ->orWhere('document_number', 'like', '%' . $value . '%');
            })
            ->where('status', 'active')
            ->take(5)
            ->get();
    }

    public function selectTeacher($id, $name)
    {
        $this->teacher_id = $id;
        $this->searchTeacher = $name;
        $this->teacherResults = [];
    }

    // ==========================================
    // CRUD
    // ==========================================

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
        ];
    }

    public function create()
    {
        $this->authorize('gestionar-carga-academica');
        $this->resetInput();
        $this->academic_period_id = $this->filterPeriodId; // Mantener periodo actual
        $this->isModalOpen = true;
    }

    public function edit(TeacherAssignment $assignment)
    {
        $this->authorize('gestionar-carga-academica');
        $this->editingAssignment = $assignment;

        // Cargar datos directos
        $this->academic_period_id = $assignment->academic_period_id;
        $this->shift_id = $assignment->shift_id;
        $this->section = $assignment->section;
        $this->max_capacity = $assignment->max_capacity;
        $this->status = $assignment->status;

        // Cargar datos de relaciones para los buscadores
        // 1. Curso
        $unit = $assignment->didacticUnit;
        $this->selectedCareerId = $unit->module->studyPlan->career_id; // Setear carrera
        $this->didactic_unit_id = $unit->id;
        $this->searchUnit = $unit->name; // Nombre en el input

        // 2. Docente
        $teacherUser = $assignment->teacher->user;
        $this->teacher_id = $assignment->teacher_id;
        $this->searchTeacher = $teacherUser->name . ' ' . $teacherUser->lastname;

        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->authorize('gestionar-carga-academica');
        $validated = $this->validate();

        // Validación de duplicidad (Curso + Sección + Periodo)
        $exists = TeacherAssignment::where('academic_period_id', $this->academic_period_id)
            ->where('didactic_unit_id', $this->didactic_unit_id)
            ->where('section', $this->section)
            ->where('id', '!=', $this->editingAssignment?->id)
            ->exists();

        if ($exists) {
            $this->addError('section', "Ya existe la sección {$this->section} para este curso.");
            return;
        }

        if ($this->editingAssignment) {
            $this->editingAssignment->update($validated);
            $msg = 'Carga actualizada correctamente.';
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
            'text' => 'Se eliminará la carga académica del docente.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'id' => $id,
            'onConfirmed' => 'deleteAssignment'
        ]);
    }

    #[On('deleteAssignment')]
    public function deleteAssignment($id)
    {
        try {
            TeacherAssignment::findOrFail($id)->delete();
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Registro eliminado.']);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se puede eliminar (posiblemente tenga alumnos matriculados).']);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInput();
    }

    private function resetInput()
    {
        $this->editingAssignment = null;

        // Reset Buscadores
        $this->selectedCareerId = '';
        $this->resetUnitSearch();
        $this->teacher_id = '';
        $this->searchTeacher = '';
        $this->teacherResults = [];

        // Reset Formulario
        $this->shift_id = '';
        $this->section = 'A';
        $this->max_capacity = 45; // [REQUERIMIENTO]
        $this->status = 'active'; // [REQUERIMIENTO]
    }

    public function render()
    {
        $query = TeacherAssignment::with(['teacher.user', 'didacticUnit', 'shift'])
            ->where('academic_period_id', $this->filterPeriodId);

        if ($this->search) {
            $query->where(function ($q) {
                $q->whereHas('didacticUnit', fn($sq) => $sq->where('name', 'like', "%{$this->search}%"))
                    ->orWhereHas('teacher.user', fn($sq) => $sq->where('name', 'like', "%{$this->search}%")->orWhere('lastname', 'like', "%{$this->search}%"));
            });
        }

        if ($this->filterCareerId) {
            $query->whereHas('didacticUnit.module.studyPlan', fn($q) => $q->where('career_id', $this->filterCareerId));
        }

        return view('livewire.pages.academic-process.teacher-assignments.teacher-assignment-manager', [
            'assignments' => $query->orderBy('id', 'desc')->paginate(10)
        ]);
    }
}
