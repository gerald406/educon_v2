<?php

namespace App\Livewire\Pages\AcademicProcess;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\PaymentConcept;
use App\Models\Registration;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Services\EnrollmentService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class RegularEnrollmentManager extends Component
{
    // --- Búsqueda ---
    public $search = '';
    public Collection $searchResults;
    public ?Student $selectedStudent = null;

    // --- Estado Académico ---
    public $activePeriod;
    public $nextSemester = 1;
    public Collection $proposalRegular;
    public Collection $proposalRecovery;

    // --- Formulario de Pago ---
    // Inicializamos como colección vacía para evitar el error "isEmpty() on null"
    public Collection $availableSeries;
    public $voucherSeries = '';
    public $voucherNumber = '';
    public $notes = '';

    public function mount()
    {
        // Inicialización segura de colecciones
        $this->searchResults = collect();
        $this->proposalRegular = collect();
        $this->proposalRecovery = collect();
        $this->availableSeries = collect(); // Evita el error 500 inicial

        // 1. Cargar Periodo Activo
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();

        // 2. Cargar Series de Comprobantes Activas
        try {
            $service = app(EnrollmentService::class);
            $this->availableSeries = $service->getActiveVoucherSeries();

            // Pre-seleccionar la primera serie si existe (ej. R25)
            if ($this->availableSeries->isNotEmpty()) {
                $this->voucherSeries = $this->availableSeries->first()->series;
            }
        } catch (\Exception $e) {
            // Si falla (ej. tabla no existe), se queda como colección vacía y no rompe la vista
        }
    }

    public function updatedSearch($value)
    {
        if (strlen($value) < 3) {
            $this->searchResults = collect();
            return;
        }

        $this->searchResults = Student::with('user', 'career')
            ->whereHas('user', function ($q) use ($value) {
                $q->where('document_number', 'like', "%$value%")
                    ->orWhere('name', 'like', "%$value%")
                    ->orWhere('lastname', 'like', "%$value%");
            })
            ->take(5)->get();
    }

    public function selectStudent($studentId)
    {
        $this->reset('voucherNumber', 'notes');

        $this->selectedStudent = Student::with('user', 'career', 'studyPlan')->find($studentId);
        $this->search = '';
        $this->searchResults = collect();

        if (!$this->activePeriod) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No hay periodo académico activo (ej. 2025-I).']);
            return;
        }

        // Validar si ya existe matrícula
        $exists = Enrollment::where('student_id', $this->selectedStudent->id)
            ->where('academic_period_id', $this->activePeriod->id)
            ->exists();

        if ($exists) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Ya Matriculado', 'text' => 'El estudiante ya está matriculado en este periodo.']);
            $this->selectedStudent = null;
            return;
        }

        // Calcular Semestre
        $this->nextSemester = ($this->selectedStudent->current_semester < 6)
            ? $this->selectedStudent->current_semester
            : 6;

        // Obtener Cursos
        $service = app(EnrollmentService::class);
        $proposal = $service->getEnrollmentProposal(
            $this->selectedStudent,
            $this->nextSemester,
            $this->activePeriod->id
        );

        $this->proposalRegular = $proposal['regular'];
        $this->proposalRecovery = $proposal['recovery'];

        // [DIAGNÓSTICO] Si no hay cursos, avisar claramente
        if ($this->proposalRegular->isEmpty() && $this->proposalRecovery->isEmpty()) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Sin Carga Académica',
                'text' => "El estudiante es apto para el Semestre {$this->nextSemester}, pero NO SE ENCONTRARON SECCIONES (Teacher Assignments) creadas para sus cursos en el periodo {$this->activePeriod->code}. Vaya al módulo 'Carga Académica' y asigne docentes y horarios."
            ]);
        }
    }

    public function confirmEnrollment()
    {
        $this->validate([
            'voucherSeries' => 'required|string',
            'voucherNumber' => 'required|numeric',
        ]);

        if ($this->proposalRegular->isEmpty() && $this->proposalRecovery->isEmpty()) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Imposible Matricular', 'text' => 'No hay cursos disponibles para inscribir (Falta programación académica).']);
            return;
        }

        try {
            DB::transaction(function () {
                $service = app(EnrollmentService::class);

                // A. Validar Voucher
                $voucher = $service->validateVoucher(
                    $this->voucherSeries,
                    $this->voucherNumber,
                    $this->selectedStudent->user_id
                );

                // B. Crear Cabecera (Matrícula)
                // Usamos 'regular' o 'regular_with_recovery'
                // NOTA: Asegúrate de haber ampliado la columna en BD como te indiqué antes, 
                // o usa 'regular' si no has podido cambiar la BD.
                $enrollmentType = $this->proposalRecovery->isNotEmpty() ? 'regular_with_recovery' : 'regular';

                $enrollment = Enrollment::create([
                    'student_id' => $this->selectedStudent->id,
                    'academic_period_id' => $this->activePeriod->id,
                    'semester_enrolled' => $this->nextSemester,
                    'enrollment_type' => $enrollmentType,
                    'status' => 'active',
                    'payment_status' => 'paid',
                    'amount_paid' => $voucher->total_amount,
                    'notes' => "Pago: {$this->voucherSeries}-{$this->voucherNumber}. " . $this->notes,
                    'registered_by_user_id' => auth()->id(),
                    'enrollment_date' => now(),
                ]);

                // C. Vincular Pago (StudentPayment) -> AQUÍ ESTABA EL ERROR
                $concept = PaymentConcept::where('code', 'MAT-REG')->first();

                StudentPayment::create([
                    'student_id' => $this->selectedStudent->id,
                    'payment_concept_id' => $concept?->id,
                    'academic_period_id' => $this->activePeriod->id,
                    'voucher_id' => $voucher->id,
                    'original_amount' => $concept?->amount ?? 0,
                    'final_amount' => $voucher->total_amount,

                    // --- CORRECCIÓN: AGREGAMOS DUE_DATE ---
                    'due_date' => now(), // <--- ¡Esto faltaba!
                    // --------------------------------------

                    'payment_date' => now(),
                    'status' => 'paid',
                    'registered_by_user_id' => auth()->id(),
                    'notes' => 'Pago automático Matrícula Regular.',
                ]);

                // D. Inscribir Cursos
                $allAssignments = $this->proposalRegular->merge($this->proposalRecovery);

                foreach ($allAssignments as $assignment) {
                    Registration::create([
                        'enrollment_id' => $enrollment->id,
                        'teacher_assignment_id' => $assignment->id,
                        'status' => 'enrolled',
                        'registration_type' => 'mandatory',
                        'registration_date' => now(),
                    ]);

                    $assignment->increment('current_enrolled');
                }

                // E. Actualizar Estudiante
                if ($this->selectedStudent->current_semester < $this->nextSemester) {
                    $this->selectedStudent->update(['current_semester' => $this->nextSemester]);
                }
            });

            $pdfUrl = route('people.students.enrollment-form', ['student' => $this->selectedStudent->id]);

            // Despachar evento Swal con botón de confirmación o cierre automático
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Matrícula Exitosa!',
                'text' => 'El estudiante ha sido matriculado. Se abrirá la ficha de matrícula.',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);

            // Despachar evento para abrir PDF en nueva pestaña
            $this->dispatch('open-pdf', url: $pdfUrl);

            // Resetear formulario
            $this->reset('selectedStudent', 'voucherNumber', 'notes', 'proposalRegular', 'proposalRecovery');
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function cancelSelection()
    {
        $this->reset('selectedStudent', 'proposalRegular', 'proposalRecovery', 'search');
        $this->searchResults = collect();
    }

    public function render()
    {
        return view('livewire.pages.academic-process.regular-enrollment-manager');
    }
}
