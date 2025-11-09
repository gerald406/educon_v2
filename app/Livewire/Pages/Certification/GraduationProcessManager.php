<?php

namespace App\Livewire\Pages\Certification;

use App\Models\GraduationProcess;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class GraduationProcessManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $student_id = '';
    public $process_type = 'thesis';
    public $title = '';
    public $abstract = '';
    public $advisor_id = null;
    public $jury_president_id = null;
    public $jury_secretary_id = null;
    public $jury_member_id = null;
    public $proposal_date = null;
    public $defense_date = null;
    public $final_grade = null;
    public $status = 'proposal';

    // --- PROPIEDADES DE ESTADO ---
    public ?GraduationProcess $editingProcess = null;
    public $isModalOpen = false;
    public $search = ''; // Búsqueda de procesos
    public $studentSearch = ''; // Búsqueda de estudiante para el modal

    // --- DATOS PARA DROPDOWNS ---
    public Collection $students;
    public Collection $teachers;

    /**
     * Hook 'mount': Carga docentes y fechas.
     */
    public function mount()
    {
        // Cargar todos los docentes para los selectores
        $this->teachers = Teacher::with('user')
            ->where('status', 'active')
            ->get()
            ->mapWithKeys(fn($t) => [$t->id => $t->user->name . ' (' . $t->code . ')']);
            
        $this->students = collect();
        $this->proposal_date = now()->format('Y-m-d');
    }

    /**
     * Hook: Busca estudiantes cuando se escribe en el modal.
     */
    public function updatedStudentSearch($value)
    {
        if (strlen($value) < 3) {
            $this->students = collect();
            return;
        }
        $this->students = Student::with('user', 'career')
            ->whereHas('user', fn($q) => $q->where('name', 'like', '%'.$value.'%'))
            ->orWhere('code', 'like', '%' . $value . '%')
            ->take(5)
            ->get();
    }

    /**
     * Acción para seleccionar un estudiante de la lista.
     */
    public function selectStudent(int $studentId)
    {
        $student = Student::with('user')->find($studentId);
        if ($student) {
            $this->student_id = $student->id;
            $this->studentSearch = $student->user->name . ' (' . $student->code . ')'; 
            $this->students = collect(); 
        }
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'student_id' => 'required|exists:students,id',
            'process_type' => 'required|in:thesis,project,sufficiency_exam',
            'title' => 'required|string|max:500',
            'abstract' => 'nullable|string',
            'advisor_id' => 'nullable|exists:teachers,id',
            'jury_president_id' => 'nullable|exists:teachers,id',
            'jury_secretary_id' => 'nullable|exists:teachers,id',
            'jury_member_id' => 'nullable|exists:teachers,id',
            'proposal_date' => 'nullable|date',
            'defense_date' => 'nullable|date|after_or_equal:proposal_date',
            'final_grade' => 'nullable|numeric|min:0|max:20',
            'status' => 'required|in:proposal,in_development,review,defended,approved,rejected',
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(GraduationProcess $process)
    {
        $this->editingProcess = $process;
        $this->fill($process);
        // Formatear fechas
        $this->proposal_date = $process->proposal_date?->format('Y-m-d');
        $this->defense_date = $process->defense_date?->format('Y-m-d');
        // Cargar el estudiante
        $this->studentSearch = $process->student->user->name;
        $this->students = Student::with('user')->where('id', $process->student_id)->get();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset();
        $this->mount();
        $this->resetValidation();
    }

    public function save()
    {
        $data = $this->validate();
        
        // [INICIO DE LA CORRECCIÓN]
        // Convertir strings vacíos a NULL para llaves foráneas y campos opcionales
        
        // Campos de Docentes (Jurados/Asesor)
        $data['advisor_id'] = $data['advisor_id'] === '' ? null : $data['advisor_id'];
        $data['jury_president_id'] = $data['jury_president_id'] === '' ? null : $data['jury_president_id'];
        $data['jury_secretary_id'] = $data['jury_secretary_id'] === '' ? null : $data['jury_secretary_id'];
        $data['jury_member_id'] = $data['jury_member_id'] === '' ? null : $data['jury_member_id'];
        
        // Campos de Fecha y Nota
        $data['defense_date'] = $data['defense_date'] === '' ? null : $data['defense_date'];
        $data['final_grade'] = $data['final_grade'] === '' ? null : $data['final_grade'];
        
        // El campo 'abstract' puede ser un string vacío, así que está bien
        $data['abstract'] = $data['abstract'] ?? null;
        // [FIN DE LA CORRECCIÓN]


        $model = $this->editingProcess ?? new GraduationProcess();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Proceso de titulación guardado correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Proceso?',
            'onConfirmed' => 'deleteProcess'
        ]);
    }

    #[On('deleteProcess')]
    public function deleteProcess(int $id)
    {
        GraduationProcess::findOrFail($id)->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Eliminado!']);
    }

    // --- RENDER ---
    public function render()
    {
        $query = GraduationProcess::with(['student.user', 'advisor.user']);

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                ->orWhereHas('student.user', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'));
        }
        
        $processes = $query->orderBy('proposal_date', 'desc')->paginate(10);

        return view('livewire.pages.certification.graduation-process-manager', [
            'processes' => $processes,
        ]);
    }
}