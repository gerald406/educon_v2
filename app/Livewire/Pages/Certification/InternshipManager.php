<?php

namespace App\Livewire\Pages\Certification;

use App\Models\Internship;
use App\Models\Student;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class InternshipManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $student_id = '';
    public $company_name = '';
    public $company_ruc = '';
    public $supervisor_name = '';
    public $start_date = '';
    public $end_date = '';
    public $total_hours = 0;
    public $evaluation_score = null;
    public $status = 'planned';

    // --- PROPIEDADES DE ESTADO ---
    public ?Internship $editingInternship = null;
    public $isModalOpen = false;
    public $search = ''; // Búsqueda de pasantías
    public $studentSearch = ''; // Búsqueda de estudiante para el modal

    // --- DATOS PARA DROPDOWNS ---
    public Collection $students;

    /**
     * Hook 'mount': Carga la fecha actual.
     */
    public function mount()
    {
        $this->start_date = now()->format('Y-m-d');
        $this->students = collect();
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
            'company_name' => 'required|string|max:200',
            'company_ruc' => 'nullable|string|digits:11',
            'supervisor_name' => 'required|string|max:200',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_hours' => 'required|integer|min:1',
            'evaluation_score' => 'nullable|numeric|min:0|max:20',
            'status' => 'required|in:planned,in_progress,completed,cancelled',
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(Internship $internship)
    {
        $this->editingInternship = $internship;
        $this->fill($internship);
        // Formatear fechas
        $this->start_date = $internship->start_date->format('Y-m-d');
        $this->end_date = $internship->end_date->format('Y-m-d');
        // Cargar el estudiante
        $this->studentSearch = $internship->student->user->name;
        $this->students = Student::with('user')->where('id', $internship->student_id)->get();
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
        
        $model = $this->editingInternship ?? new Internship();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Pasantía registrada correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Registro de Pasantía?',
            'onConfirmed' => 'deleteInternship'
        ]);
    }

    #[On('deleteInternship')]
    public function deleteInternship(int $id)
    {
        Internship::findOrFail($id)->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Eliminado!']);
    }

    // --- RENDER ---
    public function render()
    {
        $query = Internship::with(['student.user', 'student.career']);

        if ($this->search) {
            $query->where('company_name', 'like', '%' . $this->search . '%')
                ->orWhereHas('student.user', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'));
        }
        
        $internships = $query->orderBy('start_date', 'desc')->paginate(10);

        return view('livewire.pages.certification.internship-manager', [
            'internships' => $internships,
        ]);
    }
}