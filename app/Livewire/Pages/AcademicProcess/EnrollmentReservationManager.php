<?php

namespace App\Livewire\Pages\AcademicProcess;

use App\Models\AcademicPeriod;
use App\Models\EnrollmentReserve;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class EnrollmentReservationManager extends Component
{
    use WithPagination;

    // --- BÚSQUEDA ---
    public $search = '';
    public Collection $searchResults;
    public ?Student $selectedStudent = null;

    // --- FORMULARIO ---
    public $resolution_code = ''; // OBLIGATORIO
    public $reason = '';
    public $start_date = '';
    public $end_date = '';
    public $academic_period_id = '';
    public $notes = '';

    // --- ESTADO ---
    public $isModalOpen = false;
    public $activePeriod;

    public function mount()
    {
        $this->searchResults = collect();
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();

        // Pre-llenar fechas con el periodo activo por defecto
        if ($this->activePeriod) {
            $this->academic_period_id = $this->activePeriod->id;
            $this->start_date = now()->format('Y-m-d');
            $this->end_date = $this->activePeriod->end_date->format('Y-m-d');
        }
    }

    // --- LÓGICA DE BÚSQUEDA ---
    public function updatedSearch($value)
    {
        if (strlen($value) < 3) {
            $this->searchResults = collect();
            return;
        }
        $this->searchResults = Student::with('user')
            ->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $value . '%')
                ->orWhere('document_number', 'like', '%' . $value . '%'))
            ->orWhere('code', 'like', '%' . $value . '%')
            ->take(5)
            ->get();
    }

    public function selectStudent(Student $student)
    {
        $this->selectedStudent = $student;
        $this->search = $student->user->name; // Mostrar nombre en input
        $this->searchResults = collect(); // Limpiar lista
    }

    // --- CRUD ---

    public function openCreateModal()
    {
        if (!$this->selectedStudent) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Debe buscar y seleccionar un estudiante primero.']);
            return;
        }

        $this->resetValidation();
        // Limpiar formulario pero mantener estudiante
        $this->resolution_code = '';
        $this->reason = '';
        $this->notes = '';

        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate([
            'resolution_code' => 'required|string|max:50', // REGLA OPERATIVA
            'reason' => 'required|string|max:500',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'academic_period_id' => 'required|exists:academic_periods,id',
        ]);

        try {
            DB::transaction(function () {
                // 1. Crear la Reserva
                EnrollmentReserve::create([
                    'student_id' => $this->selectedStudent->id,
                    'academic_period_id' => $this->academic_period_id,
                    'resolution_code' => $this->resolution_code,
                    'reason' => $this->reason,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'status' => 'active',
                    'notes' => $this->notes,
                ]);

                // 2. Actualizar Estado del Estudiante
                $this->selectedStudent->update([
                    'academic_status' => 'enrollment_reserved' // Estado: Licencia/Reserva
                ]);

                // (Opcional) Si tenía matrícula activa, se podría anular o congelar aquí.
            });

            $this->isModalOpen = false;
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Registrado', 'text' => 'La reserva de matrícula se ha registrado correctamente bajo la Resolución ' . $this->resolution_code]);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function render()
    {
        // Listar reservas del estudiante seleccionado (si hay uno) o las últimas registradas
        $query = EnrollmentReserve::with(['student.user', 'academicPeriod'])
            ->orderBy('created_at', 'desc');

        if ($this->selectedStudent) {
            $query->where('student_id', $this->selectedStudent->id);
        }

        return view('livewire.pages.academic-process.enrollment-reservation-manager', [
            'reservations' => $query->paginate(10),
            'periods' => AcademicPeriod::orderBy('start_date', 'desc')->take(5)->get(),
        ]);
    }
}
