<?php

namespace App\Livewire\Pages\AcademicProcess;

use App\Models\AcademicPeriod;
use App\Models\Enrollment;
use App\Models\PaymentConcept;
use App\Models\Registration;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Services\EnrollmentService;
use App\Models\VoucherSeries; // <--- IMPORTANTE: Asegúrate de tener esto
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
    public Collection $availableSeries;
    public $voucherSeries = '';
    public $voucherNumber = '';
    public $notes = '';

    public function mount()
    {
        // Inicialización segura
        $this->searchResults = collect();
        $this->proposalRegular = collect();
        $this->proposalRecovery = collect();
        $this->availableSeries = collect();

        // 1. Cargar Periodo Activo
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();

        // 2. Cargar Series de Comprobantes Activas (Integración con Tesorería)
        try {
            // Usamos el servicio O directamente el modelo, ambas formas son válidas.
            // Aquí lo hacemos directo para asegurar que cargue si el servicio no tiene el método.
            $this->availableSeries = VoucherSeries::where('status', 'active')
                ->orderBy('voucher_type')
                ->orderBy('series')
                ->get();

            // Pre-seleccionar la primera serie si existe (ej. R25)
            if ($this->availableSeries->isNotEmpty()) {
                $this->voucherSeries = $this->availableSeries->first()->series;
            }
        } catch (\Exception $e) {
            // Si falla, no rompe la vista
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
            ->where('status', 'active') // Solo validamos activas, permitimos si la anterior fue anulada
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

        // Obtener Cursos desde el Servicio
        $service = app(EnrollmentService::class);
        $proposal = $service->getEnrollmentProposal(
            $this->selectedStudent,
            $this->nextSemester,
            $this->activePeriod->id
        );

        $this->proposalRegular = $proposal['regular'];
        $this->proposalRecovery = $proposal['recovery'];

        if ($this->proposalRegular->isEmpty() && $this->proposalRecovery->isEmpty()) {
            $this->dispatch('swal', [
                'icon' => 'warning',
                'title' => 'Sin Carga Académica',
                'text' => "El estudiante es apto para el Semestre {$this->nextSemester}, pero NO SE ENCONTRARON SECCIONES programadas en el periodo {$this->activePeriod->code}."
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
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Imposible Matricular', 'text' => 'No hay cursos disponibles para inscribir.']);
            return;
        }

        try {
            DB::transaction(function () {
                $service = app(EnrollmentService::class);

                // A. Validar Voucher (Serie + Número)
                $voucher = $service->validateVoucher(
                    $this->voucherSeries,
                    $this->voucherNumber,
                    $this->selectedStudent->user_id
                );

                // B. Crear Cabecera
                // Ajustamos el tipo de matrícula. Si tu BD es varchar(50) usa strings largos.
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

                // C. Vincular Pago
                $concept = PaymentConcept::where('code', 'MAT-REG')->first();

                StudentPayment::create([
                    'student_id' => $this->selectedStudent->id,
                    'payment_concept_id' => $concept?->id,
                    'academic_period_id' => $this->activePeriod->id,
                    'voucher_id' => $voucher->id,
                    'original_amount' => $concept?->amount ?? 0,
                    'final_amount' => $voucher->total_amount,
                    'due_date' => now(), // CORRECCIÓN SQL CRÍTICA
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

            // Generar URL del PDF
            // ASEGÚRATE QUE 'people.students.enrollment-form' EXISTA EN TU WEB.PHP
            $pdfUrl = route('people.students.enrollment-form', ['student' => $this->selectedStudent->id]);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Matrícula Exitosa!',
                'text' => 'Proceso completado. Se abrirá la ficha de matrícula.',
                'timer' => 2000,
                'showConfirmButton' => false
            ]);

            $this->dispatch('open-pdf', url: $pdfUrl);

            $this->reset('selectedStudent', 'voucherNumber', 'notes', 'proposalRegular', 'proposalRecovery');
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function cancelSelection()
    {
        $this->reset('selectedStudent', 'proposalRegular', 'proposalRecovery');
        $this->searchResults = collect(); // Limpiar resultados
    }

    public function render()
    {
        return view('livewire.pages.academic-process.regular-enrollment-manager');
    }
}
