<?php

namespace App\Livewire\Pages\AcademicProcess\Schedules;

use App\Models\AcademicPeriod;
use App\Models\Career;
use App\Models\ClassroomResource;
use App\Models\DidacticUnit;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;

#[Layout('layouts.app')]
class ScheduleManager extends Component
{
    // ==========================================
    // PROPIEDADES - GESTIÓN DE HORARIOS
    // ==========================================

    public ?AcademicPeriod $activePeriod = null;

    // Filtros de selección
    public $selectedCareerId = '';
    public $searchUnit = '';
    public $unitResults = [];
    public $selectedUnitId = '';
    public $selectedUnitName = '';
    public $selectedAssignmentId = '';
    public ?TeacherAssignment $selectedAssignment = null;

    // Formulario de horario
    public $classroom_resource_id = '';
    public $day_of_week = 'monday';
    public $start_time = '';
    public $end_time = '';

    // Colecciones
    public Collection $careers;
    public Collection $classrooms;
    public Collection $shifts;
    public Collection $teachers;
    public Collection $currentSchedules;
    public Collection $sectionAssignments;

    // ==========================================
    // PROPIEDADES - EXPORTACIÓN (SIMPLIFICADO)
    // ==========================================

    public $showExportModal = false;
    public $exportType = 'teacher'; // Solo: 'teacher' o 'career'
    public $exportFormat = 'pdf';   // 'pdf' o 'excel'

    // Filtros específicos por tipo
    public $exportTeacherId = '';
    public $exportCareerId = '';
    public $exportSemester = '';
    public $exportShiftId = '';

    // ==========================================
    // INICIALIZACIÓN
    // ==========================================

    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();

        $this->careers = Career::where('status', 'active')
            ->orderBy('name')
            ->get();

        $this->classrooms = ClassroomResource::where('status', 'available')
            ->orderBy('name')
            ->get();

        $this->shifts = Shift::where('status', 'active')
            ->orderBy('name')
            ->get();

        $this->teachers = Teacher::with('user')
            ->where('status', 'active')
            ->get()
            ->sortBy('user.lastname');

        $this->currentSchedules = collect();
        $this->sectionAssignments = collect();
    }

    // ==========================================
    // GESTIÓN DE HORARIOS (Sin cambios)
    // ==========================================

    public function updatedSelectedCareerId()
    {
        $this->resetUnitSearch();
    }

    public function updatedSearchUnit($value)
    {
        if (strlen($value) < 2 || !$this->selectedCareerId) {
            $this->unitResults = [];
            return;
        }

        $this->unitResults = DidacticUnit::whereHas(
            'module.studyPlan',
            fn($q) => $q->where('career_id', $this->selectedCareerId)
        )
            ->where('name', 'like', '%' . $value . '%')
            ->where('status', 'active')
            ->orderBy('semester')
            ->take(10)
            ->get();
    }

    public function selectUnit($id, $name, $semester)
    {
        $this->selectedUnitId = $id;
        $this->searchUnit = $name;
        $this->selectedUnitName = "Semestre $semester - $name";
        $this->unitResults = [];
        $this->loadAssignments();
    }

    private function resetUnitSearch()
    {
        $this->selectedUnitId = '';
        $this->searchUnit = '';
        $this->selectedUnitName = '';
        $this->unitResults = [];
        $this->selectedAssignmentId = '';
        $this->selectedAssignment = null;
        $this->currentSchedules = collect();
    }

    public function loadAssignments()
    {
        if ($this->selectedUnitId && $this->activePeriod) {
            $this->sectionAssignments = TeacherAssignment::with(['teacher.user', 'shift'])
                ->where('academic_period_id', $this->activePeriod->id)
                ->where('didactic_unit_id', $this->selectedUnitId)
                ->where('status', 'active')
                ->get();

            if ($this->sectionAssignments->count() === 1) {
                $this->selectedAssignmentId = $this->sectionAssignments->first()->id;
                $this->updatedSelectedAssignmentId($this->selectedAssignmentId);
            }
        }
    }

    public function updatedSelectedAssignmentId($value)
    {
        if ($value) {
            $this->selectedAssignment = TeacherAssignment::with(['teacher', 'didacticUnit'])->find($value);
            $this->loadSchedules();
        } else {
            $this->selectedAssignment = null;
            $this->currentSchedules = collect();
        }
    }

    public function loadSchedules()
    {
        if ($this->selectedAssignment) {
            $this->currentSchedules = $this->selectedAssignment->schedules()
                ->with('classroomResource')
                ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')")
                ->orderBy('start_time')
                ->get();
        }
    }

    public function setPresetTime($range)
    {
        if ($range === 'morning') {
            $this->start_time = '08:00';
            $this->end_time = '13:00';
        } elseif ($range === 'night') {
            $this->start_time = '17:00';
            $this->end_time = '21:30';
        }
    }

    public function addSchedule()
    {
        $this->validate([
            'day_of_week' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'classroom_resource_id' => 'nullable|exists:classroom_resources,id'
        ]);

        $conflict = $this->currentSchedules->where('day_of_week', $this->day_of_week)
            ->filter(function ($s) {
                return ($this->start_time < $s->end_time->format('H:i')) &&
                    ($this->end_time > $s->start_time->format('H:i'));
            })->isNotEmpty();

        if ($conflict) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Conflicto',
                'text' => 'Ya existe un horario en ese rango.'
            ]);
            return;
        }

        Schedule::create([
            'teacher_assignment_id' => $this->selectedAssignmentId,
            'day_of_week' => $this->day_of_week,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'classroom_resource_id' => $this->classroom_resource_id ?: null
        ]);

        $this->loadSchedules();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Agregado',
            'text' => 'Bloque horario registrado.'
        ]);
    }

    public function deleteSchedule($id)
    {
        Schedule::find($id)?->delete();
        $this->loadSchedules();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => 'Eliminado',
            'text' => 'Bloque eliminado.'
        ]);
    }
    
    // ==========================================
    // EXPORTACIÓN - LÓGICA SIMPLIFICADA
    // ==========================================

    /**
     * Resetear modal al abrirlo
     */
    public function updatedShowExportModal($value)
    {
        if ($value) {
            $this->exportType = 'teacher';
            $this->exportFormat = 'pdf';
            $this->exportTeacherId = '';
            $this->exportCareerId = '';
            $this->exportSemester = '';
            $this->exportShiftId = '';
        }
    }

    /**
     * Al cambiar tipo de reporte, limpiar campos específicos
     */
    public function updatedExportType()
    {
        $this->exportTeacherId = '';
        $this->exportCareerId = '';
        $this->exportSemester = '';
        $this->exportShiftId = '';
    }

    /**
     * Propiedad computada: Lista de semestres disponibles
     */
    #[Computed]
    public function availableSemesters()
    {
        if (!$this->exportCareerId || !$this->activePeriod) {
            return collect();
        }

        return TeacherAssignment::where('academic_period_id', $this->activePeriod->id)
            ->where('status', 'active')
            ->whereHas(
                'didacticUnit.module.studyPlan',
                fn($q) =>
                $q->where('career_id', $this->exportCareerId)
            )
            ->with('didacticUnit')
            ->get()
            ->pluck('didacticUnit.semester')
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * Validación: ¿Se puede exportar?
     */
    #[Computed]
    public function canExport()
    {
        if (!$this->activePeriod) {
            return false;
        }

        if ($this->exportType === 'teacher') {
            return !empty($this->exportTeacherId);
        }

        if ($this->exportType === 'career') {
            return !empty($this->exportCareerId) &&
                !empty($this->exportSemester) &&
                !empty($this->exportShiftId);
        }

        return false;
    }

    /**
     * Generar reporte y redireccionar
     */
    public function generateReport()
    {
        if (!$this->canExport) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Datos incompletos',
                'text' => 'Complete todos los campos requeridos.'
            ]);
            return;
        }

        // Construir parámetros
        $params = [
            'type' => $this->exportType,
            'format' => $this->exportFormat,
            'period_id' => $this->activePeriod->id,
        ];

        // Parámetros según tipo
        if ($this->exportType === 'teacher') {
            $params['id'] = $this->exportTeacherId;
        } elseif ($this->exportType === 'career') {
            $params['id'] = $this->exportCareerId;
            $params['semester'] = $this->exportSemester;
            $params['shift_id'] = $this->exportShiftId;
        }

        // Generar URL
        $url = route('academic-process.schedules.export', $params);

        // Cerrar modal
        $this->showExportModal = false;

        // Redireccionar a descarga
        // return redirect()->route('academic-process.schedules.export', $params);
        $this->js("window.open('$url', '_blank');");
    }

    public function render()
    {
        return view('livewire.pages.academic-process.schedules.schedule-manager');
    }
}
