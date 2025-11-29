<?php

namespace App\Livewire\Pages\AcademicProcess;

use App\Models\AcademicPeriod;
use App\Models\AcademicRecord;
use App\Models\DidacticUnit;
use App\Models\Enrollment;
use App\Models\Registration;
use App\Models\Student;
use App\Models\TeacherAssignment;
use App\Models\Voucher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class RegularEnrollmentManager extends Component
{
    use WithPagination;

    // --- BÚSQUEDA ---
    public $search = '';
    public Collection $searchResults;
    public ?Student $selectedStudent = null;

    // --- DATOS ACADÉMICOS (VISUALIZACIÓN) ---
    public Collection $lastSemesterRecords; // Notas del ciclo anterior
    public $nextSemester = 1;

    // --- FORMULARIO ---
    public $voucherNumber = '';
    public $notes = '';

    // --- ESTADO ---
    public $activePeriod;

    public function mount()
    {
        $this->searchResults = collect();
        $this->lastSemesterRecords = collect();
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
    }

    // --- BÚSQUEDA ---
    public function updatedSearch($value)
    {
        if (strlen($value) < 3) {
            $this->searchResults = collect();
            return;
        }

        // Buscar estudiantes Regulares o Irregulares (aptos para matrícula)
        $this->searchResults = Student::with('user', 'career')
            ->whereIn('academic_status', ['regular', 'irregular'])
            ->where(function ($q) use ($value) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', '%' . $value . '%')
                    ->orWhere('document_number', 'like', '%' . $value . '%'))
                    ->orWhere('code', 'like', '%' . $value . '%');
            })
            ->take(5)
            ->get();
    }

    public function selectStudent(Student $student)
    {
        $this->selectedStudent = $student;
        $this->search = $student->user->name;
        $this->searchResults = collect();
        $this->voucherNumber = '';

        // 1. Calcular el siguiente semestre
        // Si está en 1ro, pasa a 2do.
        $this->nextSemester = $student->current_semester + 1;
        if ($this->nextSemester > 6) $this->nextSemester = 6; // Tope (ajustar según carrera)

        // 2. Cargar historial del semestre ANTERIOR (el que acaba de cursar)
        // Buscamos notas donde la unidad didáctica pertenezca al semestre actual del estudiante
        $this->lastSemesterRecords = AcademicRecord::with('didacticUnit')
            ->where('student_id', $student->id)
            ->whereHas('didacticUnit', fn($q) => $q->where('semester', $student->current_semester))
            ->get();
    }

    // --- PROCESO DE MATRÍCULA ---
    public function processEnrollment()
    {
        $this->validate([
            'voucherNumber' => 'required|string',
            'selectedStudent' => 'required'
        ]);

        if (!$this->activePeriod) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No hay periodo activo.']);
            return;
        }

        // 1. Validar si ya está matriculado en este periodo
        $existingEnrollment = Enrollment::where('student_id', $this->selectedStudent->id)
            ->where('academic_period_id', $this->activePeriod->id)
            ->where('status', 'active')
            ->exists();

        if ($existingEnrollment) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Ya matriculado', 'text' => 'Este estudiante ya tiene matrícula en el periodo actual.']);
            return;
        }

        // 2. Validar Voucher
        $voucher = Voucher::where('number', $this->voucherNumber)
            ->where('client_id', $this->selectedStudent->user_id)
            ->where('status', 'issued')
            ->first();

        if (!$voucher) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Voucher no válido', 'text' => 'Comprobante no encontrado o no pertenece al estudiante.']);
            return;
        }

        try {
            DB::transaction(function () use ($voucher) {

                // A. Crear la Matrícula
                $enrollment = Enrollment::create([
                    'student_id' => $this->selectedStudent->id,
                    'academic_period_id' => $this->activePeriod->id,
                    'semester_enrolled' => $this->nextSemester,
                    'enrollment_type' => 'continuing', // Regular / Continuidad
                    'payment_status' => 'paid',
                    'status' => 'active',
                    'notes' => "Matrícula Regular Semestre {$this->nextSemester}. Voucher: {$voucher->series}-{$voucher->number}. " . $this->notes,
                    'amount_paid' => $voucher->total_amount,
                    'enrollment_date' => now(),
                ]);

                // B. Actualizar Estudiante (Sube de Semestre)
                $this->selectedStudent->update([
                    'current_semester' => $this->nextSemester,
                    // Podríamos recalcular si es regular o irregular basado en las notas cargadas, 
                    // pero por ahora lo mantenemos o el secretario lo cambiaría manualmente si fuera necesario.
                ]);

                // C. Inscribir Cursos del Nuevo Semestre
                // 1. Obtener módulos del plan
                $moduleIds = \App\Models\Module::where('study_plan_id', $this->selectedStudent->study_plan_id)->pluck('id');

                // 2. Unidades del semestre que le toca
                $unitsIds = DidacticUnit::whereIn('module_id', $moduleIds)
                    ->where('semester', $this->nextSemester)
                    ->pluck('id');

                // 3. Buscar secciones abiertas (Turno y Periodo)
                // OJO: Aquí asumimos un turno por defecto (ej. Mañana). 
                // Si la carrera tiene turnos fijos, habría que buscar el turno del estudiante o pedirlo.
                // Por simplicidad, buscamos cualquier sección activa del curso en el periodo.
                $assignments = TeacherAssignment::whereIn('didactic_unit_id', $unitsIds)
                    ->where('academic_period_id', $this->activePeriod->id)
                    ->where('status', 'active')
                    ->get()
                    ->unique('didactic_unit_id');

                foreach ($assignments as $assignment) {
                    Registration::create([
                        'enrollment_id' => $enrollment->id,
                        'teacher_assignment_id' => $assignment->id,
                        'status' => 'enrolled',
                        'registration_type' => 'mandatory'
                    ]);

                    $assignment->increment('current_enrolled');
                }
            });

            // D. Generar PDF y Resetear
            $pdfUrl = route('people.students.enrollment-form', $this->selectedStudent->id);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Matrícula Exitosa!',
                'text' => "Estudiante matriculado en {$this->nextSemester}° Semestre. Abriendo ficha..."
            ]);

            $this->dispatch('open-pdf', url: $pdfUrl);
            $this->reset('selectedStudent', 'search', 'lastSemesterRecords', 'voucherNumber');
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.pages.academic-process.regular-enrollment-manager');
    }
}
