<?php

namespace App\Livewire\Pages\AcademicProcess;

use App\Models\Enrollment;
use App\Models\AcademicPeriod;
use App\Services\EnrollmentService;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class EnrollmentListManager extends Component
{
    use WithPagination;

    public $search = '';
    public $academic_period_id;

    // Filtros
    public $periods;

    // Estado Modal Edición
    public $isEditModalOpen = false;
    public Enrollment $editingEnrollment;
    public $edit_notes = '';
    public $edit_semester = '';

    public function mount()
    {
        $this->periods = AcademicPeriod::orderBy('start_date', 'desc')->get();
        // Seleccionar periodo activo por defecto
        $active = $this->periods->firstWhere('status', 'active');
        $this->academic_period_id = $active ? $active->id : $this->periods->first()->id;
    }

    // --- LISTADO ---
    public function render()
    {
        $enrollments = Enrollment::with(['student.user', 'student.career', 'academicPeriod'])
            ->where('academic_period_id', $this->academic_period_id)
            ->whereHas('student', function ($q) {
                $q->whereHas(
                    'user',
                    fn($u) =>
                    $u->where('name', 'like', "%{$this->search}%")
                        ->orWhere('lastname', 'like', "%{$this->search}%")
                        ->orWhere('document_number', 'like', "%{$this->search}%")
                )->orWhere('code', 'like', "%{$this->search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.pages.academic-process.enrollment-list-manager', [
            'enrollments' => $enrollments
        ]);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    // --- ACCIÓN: ELIMINAR (LIBERAR VOUCHER) ---
    public function deleteEnrollment($id)
    {
        try {
            $service = app(EnrollmentService::class);
            $service->deleteEnrollment($id);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Matrícula Anulada',
                'text' => 'Se ha eliminado la matrícula y liberado el comprobante de pago asociado.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'title' => '¿Anular Matrícula?',
            'text' => 'Esta acción eliminará la inscripción a cursos y LIBERARÁ EL VOUCHER de pago para ser usado nuevamente. ¿Continuar?',
            'id' => $id,
            'onConfirmed' => 'deleteEnrollmentConfirmed'
        ]);
    }

    // Listener para SweetAlert
    protected $listeners = ['deleteEnrollmentConfirmed' => 'deleteEnrollment'];


    // --- ACCIÓN: EDITAR (Solo datos básicos) ---
    public function editEnrollment(Enrollment $enrollment)
    {
        $this->editingEnrollment = $enrollment;
        $this->edit_notes = $enrollment->notes;
        $this->edit_semester = $enrollment->semester_enrolled;
        $this->isEditModalOpen = true;
    }

    public function updateEnrollment()
    {
        $this->validate([
            'edit_semester' => 'required|integer|min:1|max:10',
            'edit_notes' => 'nullable|string'
        ]);

        $this->editingEnrollment->update([
            'semester_enrolled' => $this->edit_semester,
            'notes' => $this->edit_notes
        ]);

        $this->isEditModalOpen = false;
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Actualizado', 'text' => 'Datos de matrícula actualizados.']);
    }

    // --- ACCIÓN: IMPRIMIR ---
    public function printEnrollment($studentId)
    {
        // Reutilizamos la ruta que ya creamos en el paso anterior
        $url = route('people.students.enrollment-form', $studentId);
        $this->dispatch('open-pdf', url: $url);
    }
}
