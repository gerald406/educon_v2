<?php

namespace App\Livewire\Pages\AcademicProcess\TeacherAssignments;

use App\Models\AcademicPeriod;
use App\Models\DidacticUnit;
use App\Models\Shift;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Database\QueryException;
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
    public Collection $teachers;
    public Collection $didacticUnits; // Todos los cursos
    public Collection $shifts;

    /**
     * Hook 'mount': Carga el periodo activo y los dropdowns.
     */
    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();

        if ($this->activePeriod) {
            $this->teachers = Teacher::where('status', 'active')
                                ->with('user') // Cargar el nombre del usuario
                                ->get()
                                ->mapWithKeys(fn($teacher) => [$teacher->id => $teacher->user->name]);
            
            $this->didacticUnits = DidacticUnit::where('status', 'active')
                                ->orderBy('semester')
                                ->orderBy('name')
                                ->get()
                                ->mapWithKeys(fn($unit) => [$unit->id => "(Sem {$unit->semester}) - {$unit->name}"]);
            
            $this->shifts = Shift::where('status', 'active')->pluck('name', 'id');
        } else {
            // Si no hay periodo activo, inicializa vacío
            $this->teachers = collect();
            $this->didacticUnits = collect();
            $this->shifts = collect();
        }
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'teacher_id' => 'required|exists:teachers,id',
            'didactic_unit_id' => 'required|exists:didactic_units,id',
            'shift_id' => 'required|exists:shifts,id',
            'section' => 'required|string|max:5',
            'max_capacity' => 'required|integer|min:1',
            'status' => 'required|in:active,suspended,completed',
            // Regla de negocio: No duplicar asignación
            'didactic_unit_id' => [
                Rule::unique('teacher_assignments')
                    ->where('academic_period_id', $this->activePeriod?->id)
                    ->where('teacher_id', $this->teacher_id)
                    ->where('section', $this->section)
                    ->ignore($this->editingAssignment?->id)
            ],
            // TODO: Añadir validación de horas del docente (Regla #4)
            // 'teacher_id' => ['required', new MaxHoursPerTeacher($this->activePeriod?->id, $this->didactic_unit_id)],
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
        $this->fill($assignment->only(
            'teacher_id', 'didactic_unit_id', 'shift_id', 
            'section', 'max_capacity', 'status'
        ));
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset('teacher_id', 'didactic_unit_id', 'shift_id', 'section', 'max_capacity', 'status', 'editingAssignment');
        $this->resetValidation();
    }

    public function save()
    {
        if (!$this->activePeriod) return; // No guardar si no hay periodo

        $data = $this->validate();
        $data['academic_period_id'] = $this->activePeriod->id;
        
        $model = $this->editingAssignment ?? new TeacherAssignment();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Asignación de carga guardada correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Asignación?',
            'text' => 'Esto eliminará la sección y sus horarios. No se podrá matricular estudiantes.',
            'onConfirmed' => 'deleteAssignment'
        ]);
    }

    #[On('deleteAssignment')]
    public function deleteAssignment(int $id)
    {
        try {
            TeacherAssignment::findOrFail($id)->delete(); // cascade borrará horarios
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'La asignación ha sido eliminada.',
            ]);
        } catch (QueryException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se puede eliminar, es probable que tenga estudiantes matriculados.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $assignments = collect(); // Por defecto, vacío
        if ($this->activePeriod) {
            $query = TeacherAssignment::with(['teacher.user', 'didacticUnit', 'shift'])
                        ->where('academic_period_id', $this->activePeriod->id);

            if ($this->search) {
                $query->where(function($q) {
                    $q->whereHas('teacher.user', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'))
                    ->orWhereHas('didacticUnit', fn($sq) => $sq->where('name', 'like', '%'.$this->search.'%'))
                    ->orWhere('section', 'like', '%' . $this->search . '%');
                });
            }
            $assignments = $query->orderBy('id')->paginate(10);
        }

        return view('livewire.pages.academic-process.teacher-assignments.teacher-assignment-manager', [
            'assignments' => $assignments,
        ]);
    }
}