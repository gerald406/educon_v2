<?php

namespace App\Livewire\Pages\AcademicProcess\TeacherAssignments;

use App\Models\AcademicPeriod;
use App\Models\DidacticUnit; // <-- [NUEVO] Importar modelo
use App\Models\Shift;
use App\Models\Teacher; // <-- [NUEVO] Importar modelo
use App\Models\TeacherAssignment;
use App\Services\TeacherWorkloadService; // <-- [NUEVO] Importar el servicio
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TeacherAssignmentManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $teacher_id = '';
    public $didactic_unit_id = '';
    public $shift_id = '';
    public $section = 'A';
    public $max_capacity = 30;
    public $status = 'active';

    // --- PROPIEDADES DE ESTADO ---
    public ?TeacherAssignment $editingAssignment = null;
    public $isModalOpen = false;
    public $search = '';

    // --- DATOS DEL CONTEXTO ---
    public ?AcademicPeriod $activePeriod = null;
    public $teachers = [];
    public $didacticUnits = [];
    public $shifts = [];

    // --- [NUEVO] Propiedad para el servicio ---
    protected TeacherWorkloadService $workloadService;

    /**
     * [NUEVO] Inyectar el servicio en el constructor.
     */
    public function __construct()
    {
        $this->workloadService = new TeacherWorkloadService();
    }

    /**
     * Hook 'mount': Carga el periodo activo y los dropdowns.
     */
    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();

        if ($this->activePeriod) {
            $this->loadDropdownData();
        }
    }

    /**
     * Cargar datos para los dropdowns
     */
    private function loadDropdownData()
    {
        // Cargar teachers con manejo de null
        $teachersList = Teacher::where('status', 'active')
            ->with('user')
            ->get();

        $this->teachers = [];
        foreach ($teachersList as $teacher) {
            if ($teacher->user) {
                $this->teachers[$teacher->id] = $teacher->user->name . ' (' . $teacher->code . ')';
            } else {
                $this->teachers[$teacher->id] = 'Profesor #' . $teacher->code;
            }
        }

        // Cargar unidades didácticas
        $unitsList = DidacticUnit::where('status', 'active')
            ->orderBy('semester')
            ->orderBy('name')
            ->get();

        $this->didacticUnits = [];
        foreach ($unitsList as $unit) {
            $this->didacticUnits[$unit->id] = $unit->name_with_semester; // Usamos el accessor
        }

        // Cargar turnos
        $this->shifts = Shift::where('status', 'active')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        $rules = [
            'teacher_id' => 'required|exists:teachers,id',
            'didactic_unit_id' => 'required|exists:didactic_units,id',
            'shift_id' => 'required|exists:shifts,id',
            'section' => 'required|string|max:5',
            'max_capacity' => 'required|integer|min:1|max:100',
            'status' => 'required|in:active,suspended,completed',
        ];

        // Regla adicional para evitar duplicados
        if ($this->activePeriod) {
            $uniqueRule = Rule::unique('teacher_assignments')
                ->where(function ($query) {
                    return $query->where('academic_period_id', $this->activePeriod->id)
                                 ->where('didactic_unit_id', $this->didactic_unit_id)
                                 ->where('section', $this->section)
                                 ->where('shift_id', $this->shift_id);
                });

            if ($this->editingAssignment) {
                $uniqueRule->ignore($this->editingAssignment->id);
            }

            $rules['section'] = ['required', 'string', 'max:5', $uniqueRule];
        }

        return $rules;
    }

    /**
     * Mensajes de validación personalizados
     */
    protected function messages()
    {
        return [
            'teacher_id.required' => 'Debe seleccionar un docente.',
            'didactic_unit_id.required' => 'Debe seleccionar una unidad didáctica.',
            'shift_id.required' => 'Debe seleccionar un turno.',
            'section.required' => 'La sección es requerida.',
            'section.unique' => 'Ya existe una asignación para esta unidad, sección y turno en el periodo actual.',
            'max_capacity.required' => 'La capacidad máxima es requerida.',
            'max_capacity.min' => 'La capacidad debe ser al menos 1.',
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(TeacherAssignment $assignment)
    {
        $this->editingAssignment = $assignment;
        $this->teacher_id = $assignment->teacher_id;
        $this->didactic_unit_id = $assignment->didactic_unit_id;
        $this->shift_id = $assignment->shift_id;
        $this->section = $assignment->section;
        $this->max_capacity = $assignment->max_capacity;
        $this->status = $assignment->status;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['teacher_id', 'didactic_unit_id', 'shift_id', 'section', 'max_capacity', 'status', 'editingAssignment']);
        $this->resetValidation();
        
        $this->section = 'A';
        $this->max_capacity = 30;
        $this->status = 'active';
    }

    /**
     * [MÉTODO SAVE MODIFICADO]
     */
    public function save()
    {
        if (!$this->activePeriod) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No hay un periodo académico activo.',
            ]);
            return;
        }

        try {
            // 1. Validar los datos del formulario (como lo tenías)
            $validatedData = $this->validate();
            
            // --- [NUEVA LÓGICA DE VALIDACIÓN DE CARGA HORARIA] ---
            $teacher = Teacher::find($validatedData['teacher_id']);
            $unit = DidacticUnit::find($validatedData['didactic_unit_id']);

            if (!$teacher || !$unit) {
                $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'El docente o la unidad didáctica no se encontraron.']);
                return;
            }

            // Llamamos al servicio para verificar
            if ($this->workloadService->wouldExceedMaxHours($teacher, $this->activePeriod, $unit, $this->editingAssignment)) {
                
                // Si excede, calculamos las horas actuales para mostrar un mensaje claro
                $currentHours = $this->workloadService->calculateWeeklyHours($teacher, $this->activePeriod);
                
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'Carga Horaria Excedida',
                    'text' => "No se puede asignar. El docente ya tiene {$currentHours} horas. Añadir este curso ({$unit->weekly_hours}h) excedería el límite de 26 horas.",
                    'toast' => false,
                    'position' => 'center',
                    'timer' => null,
                    'showConfirmButton' => true,
                ]);
                return; // Detener el guardado
            }
            // --- [FIN DE LA NUEVA LÓGICA] ---

            
            // 2. Continuar con la lógica de guardado (como lo tenías)
            $validatedData['academic_period_id'] = $this->activePeriod->id;
            
            if (!$this->editingAssignment) {
                $validatedData['current_enrolled'] = 0;
            }
            
            if ($this->editingAssignment) {
                $this->editingAssignment->update($validatedData);
                $message = 'Asignación actualizada correctamente.';
            } else {
                TeacherAssignment::create($validatedData);
                $message = 'Asignación creada correctamente.';
            }
            
            $this->closeModal();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => $message,
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error al guardar la asignación: ' . $e->getMessage(),
            ]);
        }
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Asignación?',
            'text' => 'Esto eliminará la sección y sus horarios asociados. Esta acción no se puede deshacer.',
            'icon' => 'warning',
            'onConfirmed' => 'deleteAssignment'
        ]);
    }

    #[On('deleteAssignment')]
    public function deleteAssignment(int $id)
    {
        try {
            // $id = is_array($data) ? $data['id'] : $data;
            
            $assignment = TeacherAssignment::findOrFail($id);
            
            if ($assignment->current_enrolled > 0) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'No se puede eliminar',
                    'text' => 'Esta asignación tiene estudiantes matriculados.',
                ]);
                return;
            }
            
            if ($assignment->schedules()->exists()) {
                 // Borramos los horarios primero
                $assignment->schedules()->delete();
            }
            
            $assignment->delete();
            
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'La asignación ha sido eliminada correctamente.',
            ]);
            
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'Ocurrió un error inesperado: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Resetear paginación cuando se busca
     */
    public function updatedSearch()
    {
        $this->resetPage();
    }

    // --- RENDER ---
    public function render()
    {
        $assignments = collect();
        
        if ($this->activePeriod) {
            $query = TeacherAssignment::with(['teacher.user', 'didacticUnit.module', 'shift'])
                ->where('academic_period_id', $this->activePeriod->id);

            if ($this->search) {
                $searchTerm = '%' . $this->search . '%';
                
                $query->where(function($q) use ($searchTerm) {
                    $q->whereHas('teacher.user', function($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('teacher', function($subQuery) use ($searchTerm) {
                        $subQuery->where('code', 'like', $searchTerm);
                    })
                    ->orWhereHas('didacticUnit', function($subQuery) use ($searchTerm) {
                        $subQuery->where('name', 'like', $searchTerm)
                                 ->orWhere('code', 'like', $searchTerm);
                    })
                    ->orWhere('section', 'like', $searchTerm);
                });
            }
            
            $assignments = $query->orderBy('created_at', 'desc')
                                ->paginate(10);
        }

        return view('livewire.pages.academic-process.teacher-assignments.teacher-assignment-manager', [
            'assignments' => $assignments,
        ]);
    }
}