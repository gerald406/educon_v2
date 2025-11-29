<?php

namespace App\Livewire\Pages\AcademicProcess;

use App\Models\AcademicPeriod;
use App\Models\DidacticUnit;
use App\Models\Enrollment;
use App\Models\EnrollmentReserve;
use App\Models\Module;
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
class ReincorporationManager extends Component
{
    use WithPagination;

    // --- BÚSQUEDA ---
    public $search = '';
    public Collection $searchResults;
    public ?Student $selectedStudent = null;
    public ?EnrollmentReserve $lastReservation = null;

    // --- FORMULARIO ---
    public $voucherNumber = '';
    public $semesterEnrolled = ''; // Ahora se calculará automáticamente
    public $notes = '';

    // --- ESTADO ---
    public $isModalOpen = false;
    public $activePeriod;

    public function mount()
    {
        $this->searchResults = collect();
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
    }

    // ... (updatedSearch sin cambios) ...
    public function updatedSearch($value)
    {
        if (strlen($value) < 3) {
            $this->searchResults = collect();
            return;
        }
        $this->searchResults = Student::with('user', 'career')
            ->where('academic_status', 'enrollment_reserved')
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

        $this->lastReservation = EnrollmentReserve::where('student_id', $student->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // [CORRECCIÓN 1] Lógica Automática de Semestre
        // Si se quedó en el semestre 3, se reincorpora al 3.
        $this->semesterEnrolled = $student->current_semester;
    }

    public function openReincorporationModal()
    {
        if (!$this->selectedStudent) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Seleccione un estudiante primero.']);
            return;
        }

        if (!$this->activePeriod) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No hay periodo activo.']);
            return;
        }

        $this->resetValidation();
        $this->voucherNumber = '';
        $this->notes = '';
        $this->isModalOpen = true;
    }

    public function processReincorporation()
    {
        $this->validate([
            'voucherNumber' => 'required|string',
        ]);

        // Validar Voucher
        $voucher = Voucher::where('number', $this->voucherNumber)
            ->where('client_id', $this->selectedStudent->user_id)
            ->where('status', 'issued')
            ->first();

        if (!$voucher) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Voucher no válido',
                'text' => 'Comprobante no encontrado o no pertenece al estudiante.'
            ]);
            return;
        }

        try {
            DB::transaction(function () use ($voucher) {

                // 1. Cerrar Reserva (FIX: Verifica el valor correcto del ENUM)
                if ($this->lastReservation) {
                    // Opciones comunes: 'completed', 'finished', 'closed', 'ended'
                    // Verifica en tu migración cuál es el valor correcto
                    $this->lastReservation->update(['status' => 'expired']);
                    // Si el error persiste, prueba: 'finished', 'closed', o 'inactive'
                }

                // 2. [FIX] Crear o Reactivar Matrícula (ASIGNAR A VARIABLE)
                $enrollment = Enrollment::updateOrCreate(
                    [
                        'student_id' => $this->selectedStudent->id,
                        'academic_period_id' => $this->activePeriod->id,
                    ],
                    [
                        'semester_enrolled' => $this->semesterEnrolled,
                        'enrollment_type' => 'reincorporation',
                        'payment_status' => 'paid',
                        'status' => 'active',
                        'notes' => "Reincorporación. Voucher: {$voucher->series}-{$voucher->number}. " . $this->notes,
                        'amount_paid' => $voucher->total_amount,
                        'enrollment_date' => now(),
                    ]
                );

                // 3. Actualizar Estado del Estudiante
                $this->selectedStudent->update([
                    'academic_status' => 'regular',
                    'current_semester' => $this->semesterEnrolled
                ]);

                // 4. [FIX] Inscripción Automática de Cursos
                // Obtener módulos del plan de estudios
                $moduleIds = DB::table('modules')
                    ->where('study_plan_id', $this->selectedStudent->study_plan_id)
                    ->pluck('id');

                // Obtener unidades didácticas del semestre
                $unitsIds = DidacticUnit::whereIn('module_id', $moduleIds)
                    ->where('semester', $this->semesterEnrolled)
                    ->pluck('id');

                // Buscar secciones abiertas
                $assignments = TeacherAssignment::whereIn('didactic_unit_id', $unitsIds)
                    ->where('academic_period_id', $this->activePeriod->id)
                    ->where('status', 'active')
                    ->get()
                    ->unique('didactic_unit_id');

                foreach ($assignments as $assignment) {
                    // [FIX] Ahora $enrollment está definido
                    $registration = Registration::firstOrCreate(
                        [
                            'enrollment_id' => $enrollment->id,
                            'teacher_assignment_id' => $assignment->id,
                        ],
                        [
                            'status' => 'enrolled',
                            'registration_type' => 'mandatory'
                        ]
                    );

                    // Solo incrementar si se creó el registro
                    if ($registration->wasRecentlyCreated) {
                        $assignment->increment('current_enrolled');
                    }
                }
            });

            $this->isModalOpen = false;
            // [NUEVO] Generar URL de la Ficha de Matrícula
            // Usamos la ruta que creamos en la Fase 119
            $pdfUrl = route('people.students.enrollment-form', $this->selectedStudent->id);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Reincorporado!',
                'text' => 'Estudiante reincorporado y matriculado en todos los cursos del semestre.'
            ]);
            // [NUEVO] Evento para abrir el PDF
            $this->dispatch('open-pdf', url: $pdfUrl);
            
            $this->reset('selectedStudent', 'search', 'lastReservation');
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => $e->getMessage()
            ]);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function render()
    {
        return view('livewire.pages.academic-process.reincorporation-manager');
    }
}
