<?php

namespace App\Livewire\Pages\Services\Tutoring;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Tutoring;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TutoringManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $student_id = '';
    public $teacher_id = '';
    public $tutoring_date = '';
    public $tutoring_type = 'academic';
    public $reason = '';
    public $session_development = '';
    public $agreements_commitments = '';
    public $follow_up_required = false;
    public $status = 'scheduled';

    // --- PROPIEDADES DE ESTADO ---
    public ?Tutoring $editingTutoring = null;
    public $isModalOpen = false;
    public $search = '';

    // --- DATOS PARA DROPDOWNS ---
    public $studentSearch = '';
    public Collection $students;
    public Collection $teachers;

    public function mount()
    {
        $this->tutoring_date = now()->format('Y-m-d\TH:i');
        $this->students = collect();
        // Cargar todos los docentes para el selector de Tutor
        $this->teachers = Teacher::with('user')
            ->where('status', 'active')
            ->get()
            ->mapWithKeys(fn($t) => [$t->id => $t->user->name . ' (' . $t->code . ')']);
    }

    // --- BÚSQUEDA DE ESTUDIANTE ---
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

    public function selectStudent(int $studentId)
    {
        $student = Student::with('user')->find($studentId);
        if ($student) {
            $this->student_id = $student->id;
            $this->studentSearch = $student->user->name . ' (' . $student->code . ')'; 
            $this->students = collect(); 
        }
    }

    protected function rules()
    {
        return [
            'student_id' => 'required|exists:students,id',
            'teacher_id' => 'required|exists:teachers,id',
            'tutoring_date' => 'required|date',
            'tutoring_type' => 'required|in:academic,personal,vocational,group',
            'reason' => 'required|string|min:10',
            'session_development' => 'nullable|string',
            'agreements_commitments' => 'nullable|string',
            'follow_up_required' => 'boolean',
            'status' => 'required|in:scheduled,completed,cancelled,rescheduled',
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(Tutoring $tutoring)
    {
        $this->editingTutoring = $tutoring;
        $this->fill($tutoring);
        $this->tutoring_date = $tutoring->tutoring_date->format('Y-m-d\TH:i');
        
        // Cargar el estudiante
        $this->studentSearch = $tutoring->student->user->name;
        $this->students = Student::with('user')->where('id', $tutoring->student_id)->get();
        
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
        $this->mount(); // Recarga valores por defecto
        $this->resetValidation();
    }

    public function save()
    {
        $data = $this->validate();
        
        $model = $this->editingTutoring ?? new Tutoring();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Sesión de tutoría registrada correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Sesión?',
            'onConfirmed' => 'deleteTutoring'
        ]);
    }

    #[On('deleteTutoring')]
    public function deleteTutoring(int $id)
    {
        Tutoring::findOrFail($id)->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Eliminado!']);
    }

    // --- RENDER ---
    public function render()
    {
        $query = Tutoring::with(['student.user', 'teacher.user']);

        if ($this->search) {
            $query->whereHas('student.user', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
                ->orWhereHas('teacher.user', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'));
        }
        
        $tutorings = $query->orderBy('tutoring_date', 'desc')->paginate(10);

        return view('livewire.pages.services.tutoring.tutoring-manager', [
            'tutorings' => $tutorings,
        ]);
    }
}