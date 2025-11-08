<?php

namespace App\Livewire\Pages\AcademicProcess\Schedules;

use App\Models\AcademicPeriod;
use App\Models\Career;
use App\Models\ClassroomResource;
use App\Models\DidacticUnit;
use App\Models\Schedule;
use App\Models\TeacherAssignment;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('layouts.app')]
class ScheduleManager extends Component
{
    // --- PROPIEDADES DEL FORMULARIO ---
    public $classroom_resource_id = '';
    public $day_of_week = 'monday';
    public $start_time = '';
    public $end_time = '';

    // --- PROPIEDADES DE ESTADO Y FILTROS ---
    public ?AcademicPeriod $activePeriod = null;
    public ?TeacherAssignment $selectedAssignment = null;
    public $selectedCareerId = '';
    public $selectedUnitId = '';
    public $selectedAssignmentId = ''; // El ID de la TeacherAssignment

    // --- ARRAYS PARA DROPDOWNS ---
    public $careers = [];
    public $availableUnits = [];
    public $availableAssignments = [];
    public $availableClassrooms = [];
    
    // Colección de horarios de la asignación seleccionada
    public Collection $currentSchedules;
    
    // Regla de negocio: Día de preparación del docente
    public $teacherPreparationDay = null;


    /**
     * Hook 'mount': Carga datos iniciales.
     */
    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        
        // Cargar carreras como array
        $this->careers = Career::where('status', 'active')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
            
        // Cargar aulas como array
        $this->availableClassrooms = ClassroomResource::where('status', 'available')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
        
        // Inicializar arrays vacíos
        $this->availableUnits = [];
        $this->availableAssignments = [];
        $this->currentSchedules = collect();

        // Seleccionar la primera carrera por defecto si existe
        if (!empty($this->careers)) {
            $this->selectedCareerId = array_key_first($this->careers);
            $this->updatedSelectedCareerId($this->selectedCareerId);
        }
    }

    // --- LÓGICA DE FILTROS DEPENDIENTES ---

    /**
     * Cuando se selecciona una carrera
     */
    public function updatedSelectedCareerId($value)
    {
        // Resetear selecciones dependientes
        $this->selectedUnitId = '';
        $this->selectedAssignmentId = '';
        $this->availableUnits = [];
        $this->availableAssignments = [];
        $this->currentSchedules = collect();
        $this->selectedAssignment = null;
        
        if (!empty($value)) {
            // Cargar unidades didácticas de la carrera seleccionada
            $units = DidacticUnit::whereHas('module.studyPlan.career', function($query) use ($value) {
                    $query->where('id', $value);
                })
                ->where('status', 'active')
                ->orderBy('semester')
                ->orderBy('name')
                ->get();
            
            // Construir el array con el accessor 'name_with_semester'
            $this->availableUnits = $units->mapWithKeys(function ($unit) {
                return [$unit->id => $unit->name_with_semester];
            })->toArray(); // Importante: convertir a array
        }
    }

    /**
     * Cuando se selecciona una unidad didáctica
     */
    public function updatedSelectedUnitId($value)
    {

        // Resetear selecciones dependientes
        $this->selectedAssignmentId = '';
        $this->availableAssignments = [];
        $this->currentSchedules = collect();
        $this->selectedAssignment = null;
        
        if (!empty($value) && $this->activePeriod) {
            // Cargar asignaciones de la unidad seleccionada
            $assignments = TeacherAssignment::where('academic_period_id', $this->activePeriod->id)
                ->where('didactic_unit_id', $value)
                ->where('status', 'active')
                ->with(['teacher.user', 'shift']) // Carga ansiosa de relaciones
                ->orderBy('section')
                ->get();
            // dd($assignments);
            // Construir el array con formato personalizado
            $this->availableAssignments = $assignments->mapWithKeys(function($assignment) {
                $teacherName = $assignment->teacher->user->name ?? 'Sin asignar';
                $shiftName = $assignment->shift->name ?? 'Sin turno';
                return [
                    $assignment->id => "Sección {$assignment->section} - {$shiftName} - Prof. {$teacherName}"
                ];
            })->toArray(); // Importante: convertir a array
        }
    }

    /**
     * Cuando se selecciona una asignación (sección)
     */
    public function updatedSelectedAssignmentId($value)
    {
        if (empty($value)) {
            $this->selectedAssignment = null;
            $this->currentSchedules = collect();
            $this->teacherPreparationDay = null;
            $this->resetFormFields();
            return;
        }

        // Cargar la asignación seleccionada con sus relaciones
        $this->selectedAssignment = TeacherAssignment::with(['teacher', 'didacticUnit'])
            ->find($value);
        
        if ($this->selectedAssignment) {
            // Cargar el día de preparación del docente (Regla de Negocio)
            $this->teacherPreparationDay = $this->selectedAssignment->teacher->preparation_day;
            
            // Cargar los horarios existentes
            $this->loadSchedules();
        }
    }

    /**
     * Carga los horarios de la asignación seleccionada
     */
    public function loadSchedules()
    {
        if ($this->selectedAssignment) {
            $this->currentSchedules = $this->selectedAssignment->schedules()
                ->with('classroomResource')
                ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')")
                ->orderBy('start_time')
                ->get();
        } else {
            $this->currentSchedules = collect();
        }
    }
    
    /**
     * Resetea los campos del formulario
     */
    private function resetFormFields()
    {
        $this->classroom_resource_id = '';
        $this->day_of_week = 'monday';
        $this->start_time = '';
        $this->end_time = '';
    }
    
    /**
     * Reglas de validación
     */
    protected function rules()
    {
        $rules = [
            'classroom_resource_id' => 'nullable|exists:classroom_resources,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ];
        
        // Agregar validación para día de preparación si existe
        if ($this->teacherPreparationDay) {
            $rules['day_of_week'] = ['required', 'in:monday,tuesday,wednesday,thursday,friday,saturday', Rule::notIn([$this->teacherPreparationDay])];
        }
        
        return $rules;
    }
    
    /**
     * Mensajes de validación personalizados
     */
    protected function messages()
    {
        return [
            'day_of_week.not_in' => 'No se puede asignar horario en el día de preparación del docente.',
            'start_time.required' => 'La hora de inicio es requerida.',
            'end_time.required' => 'La hora de fin es requerida.',
            'end_time.after' => 'La hora de fin debe ser posterior a la hora de inicio.',
        ];
    }

    /**
     * Añadir un nuevo bloque de horario
     */
    public function addSchedule()
    {
        $this->validate();
        
        // Verificar conflictos de horario antes de guardar
        if ($this->hasScheduleConflict()) {
            $this->addError('start_time', 'Existe un conflicto de horario con otro bloque existente.');
            return;
        }
        
        try {
            Schedule::create([
                'teacher_assignment_id' => $this->selectedAssignment->id,
                'classroom_resource_id' => $this->classroom_resource_id ?: null,
                'day_of_week' => $this->day_of_week,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
            ]);
            
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Bloque de horario añadido correctamente.'
            ]);
            
            $this->loadSchedules();
            $this->resetFormFields();
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo añadir el horario. Por favor, intente nuevamente.'
            ]);
        }
    }
    
    /**
     * Verificar si hay conflicto de horario
     */
    private function hasScheduleConflict(): bool
    {
        return $this->currentSchedules
            ->where('day_of_week', $this->day_of_week)
            ->filter(function ($schedule) {
                $newStart = strtotime($this->start_time);
                $newEnd = strtotime($this->end_time);
                $existingStart = strtotime($schedule->start_time);
                $existingEnd = strtotime($schedule->end_time);
                
                // Verificar solapamiento
                return ($newStart < $existingEnd && $newEnd > $existingStart);
            })
            ->isNotEmpty();
    }

    /**
     * Confirmar eliminación de horario
     */
    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar bloque de horario?',
            'text' => 'Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'confirmButtonText' => 'Sí, eliminar',
            'cancelButtonText' => 'Cancelar',
            'onConfirmed' => 'deleteSchedule'
        ]);
    }

    /**
     * Eliminar horario
     */
    #[On('deleteSchedule')]
    public function deleteSchedule(int $id)
    {
        try {
            // Maneja el payload que puede ser un int o un array ['id' => 1]
            // $id = is_array($data) ? $data['id'] : $data;
            
            Schedule::findOrFail($id)->delete();
            
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El bloque de horario ha sido eliminado.'
            ]);
            
            $this->loadSchedules(); // Recargar la lista de horarios
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar el horario.'
            ]);
        }
    }

    /**
     * Render
     */
    public function render()
    {
        return view('livewire.pages.academic-process.schedules.schedule-manager');
    }
}