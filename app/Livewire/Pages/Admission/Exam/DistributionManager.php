<?php

namespace App\Livewire\Pages\Admission\Exam;

use App\Models\AdmissionOffering;
use App\Models\Applicant;
use App\Models\ExamClassroom;
use App\Models\ExamClassroomAssignment;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DistributionManager extends Component
{
    // Filtros
    public $selectedOfferingId = '';

    // Métricas
    public $totalApplicants = 0;
    public $assignedApplicants = 0;
    public $totalCapacity = 0;

    public function mount()
    {
        $this->refreshMetrics();
    }

    public function refreshMetrics()
    {
        // Total de postulantes aptos (registrados)
        $this->totalApplicants = Applicant::where('application_status', 'registered')->count(); // O 'apto'

        // Total ya asignados
        $this->assignedApplicants = ExamClassroomAssignment::count();

        // Capacidad total de aulas activas
        $this->totalCapacity = ExamClassroom::where('is_active', true)->sum('capacity');
    }

    public function autoDistribute()
    {
        // 1. Obtener postulantes SIN aula asignada
        // Filtramos por Oferta si se seleccionó una, si no, todos los aptos
        $query = Applicant::where('application_status', 'registered') // Ajustar estado según tu flujo
            ->whereDoesntHave('examAssignment'); // Relación en Applicant: hasOne(Assignment)

        if ($this->selectedOfferingId) {
            $query->where('admission_offering_id', $this->selectedOfferingId);
        }

        $applicants = $query->get();

        if ($applicants->isEmpty()) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Sin postulantes', 'text' => 'No hay postulantes pendientes de distribución.']);
            return;
        }

        // 2. Obtener aulas disponibles con espacio
        $classrooms = ExamClassroom::where('is_active', true)
            ->withCount('assignments') // Cuenta asignados actuales: assignments_count
            ->get();

        // Calcular espacio disponible real
        $availableClassrooms = $classrooms->map(function ($room) {
            $room->available_slots = $room->capacity - $room->assignments_count;
            return $room;
        })->filter(function ($room) {
            return $room->available_slots > 0;
        })->values(); // Reindexar

        if ($availableClassrooms->isEmpty()) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Sin Espacio', 'text' => 'No hay aulas con capacidad disponible.']);
            return;
        }

        // 3. Algoritmo de Distribución (Replicando CodeIgniter)
        // Mezclar postulantes para aleatoriedad
        $applicants = $applicants->shuffle();

        $assignmentsToInsert = [];
        $assignedCount = 0;

        DB::transaction(function () use ($applicants, $availableClassrooms, &$assignedCount) {
            foreach ($applicants as $applicant) {
                // ORDENAR AULAS: Priorizar la que tiene MÁS espacio libre (Balanceo de carga)
                // Esto se hace dentro del loop para mantener el balance dinámico
                $availableClassrooms = $availableClassrooms->sortByDesc('available_slots')->values();

                $bestRoom = $availableClassrooms->first();

                // Si la mejor aula ya no tiene espacio (significa que todas están llenas)
                if ($bestRoom->available_slots <= 0) {
                    break; // Detener asignación
                }

                // Asignar
                ExamClassroomAssignment::create([
                    'exam_classroom_id' => $bestRoom->id,
                    'applicant_id' => $applicant->id,
                    'assigned_at' => now(),
                ]);

                // Actualizar contador en memoria
                $bestRoom->available_slots--;
                $assignedCount++;
            }
        });

        $this->refreshMetrics();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Éxito', 'text' => "Se distribuyeron {$assignedCount} postulantes."]);
    }

    public function resetDistribution()
    {
        // Limpiar todas las asignaciones
        ExamClassroomAssignment::truncate();
        $this->refreshMetrics();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Reseteado', 'text' => 'Todas las asignaciones han sido eliminadas.']);
    }

    public function render()
    {
        // Listado de Aulas con su ocupación para monitoreo
        $classroomsStatus = ExamClassroom::with('pavilion')
            ->withCount('assignments')
            ->get();

        return view('livewire.pages.admission.exam.distribution-manager', [
            'offerings' => AdmissionOffering::with('career')->get(),
            'classroomsStatus' => $classroomsStatus
        ]);
    }
}
