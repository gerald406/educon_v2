<?php

namespace App\Livewire\Pages\Admission\Exam;

use App\Models\AdmissionModality;
use App\Models\AdmissionOffering;
use App\Models\Applicant;
use App\Models\Career;
use App\Models\ExamClassroom;
use App\Models\ExamClassroomAssignment;
use App\Models\Shift;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class DistributionManager extends Component
{
    // --- FILTROS ---
    public $filterModality = '';
    public $filterCareer = '';
    public $filterShift = '';

    // --- ESTADO ---
    public $unassignedCount = 0;
    public $distributedCount = 0;

    // --- METRICAS ---
    public $totalCapacity = 0;
    public $usedCapacity = 0;

    public function mount()
    {
        $this->refreshStats();
    }

    public function refreshStats()
    {
        // 1. Contar postulantes sin aula (Aptos/Registrados)
        $query = Applicant::where('application_status', 'registered')
            ->whereDoesntHave('examAssignment');

        if ($this->filterModality) $query->where('admission_modality_id', $this->filterModality);
        if ($this->filterCareer) {
            $query->whereHas('admissionOffering', fn($q) => $q->where('career_id', $this->filterCareer));
        }
        if ($this->filterShift) {
            $query->whereHas('admissionOffering', fn($q) => $q->where('shift_id', $this->filterShift));
        }

        $this->unassignedCount = $query->count();

        // 2. Contar distribuidos
        $this->distributedCount = ExamClassroomAssignment::count();

        // 3. Capacidad de Aulas
        $this->totalCapacity = ExamClassroom::where('is_active', true)->sum('capacity');
        $this->usedCapacity = $this->distributedCount; // Asumiendo 1 a 1
    }

    // --- ACCIÓN: DISTRIBUCIÓN AUTOMÁTICA ---
    public function autoDistribute()
    {
        // 1. Obtener postulantes filtrados
        $query = Applicant::where('application_status', 'registered')
            ->whereDoesntHave('examAssignment');

        // Aplicar los mismos filtros de la vista
        if ($this->filterModality) $query->where('admission_modality_id', $this->filterModality);
        if ($this->filterCareer) {
            $query->whereHas('admissionOffering', fn($q) => $q->where('career_id', $this->filterCareer));
        }
        if ($this->filterShift) {
            $query->whereHas('admissionOffering', fn($q) => $q->where('shift_id', $this->filterShift));
        }

        $applicants = $query->get();

        if ($applicants->isEmpty()) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Sin postulantes', 'text' => 'No hay postulantes pendientes con estos filtros.']);
            return;
        }

        // 2. Obtener aulas disponibles
        $classrooms = ExamClassroom::where('is_active', true)
            ->withCount('assignments')
            ->get()
            ->map(function ($room) {
                $room->available_slots = $room->capacity - $room->assignments_count;
                return $room;
            })
            ->filter(fn($r) => $r->available_slots > 0)
            ->values();

        if ($classrooms->isEmpty()) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Aulas Llenas', 'text' => 'No hay espacio físico disponible.']);
            return;
        }

        // 3. Algoritmo de Distribución (Balanceado)
        $applicants = $applicants->shuffle(); // Aleatorio
        $assignedCount = 0;

        DB::transaction(function () use ($applicants, $classrooms, &$assignedCount) {
            foreach ($applicants as $applicant) {
                // Reordenar: siempre elegir el aula con MÁS espacio libre actual
                $classrooms = $classrooms->sortByDesc('available_slots')->values();
                $bestRoom = $classrooms->first();

                if ($bestRoom->available_slots <= 0) break;

                ExamClassroomAssignment::create([
                    'exam_classroom_id' => $bestRoom->id,
                    'applicant_id' => $applicant->id,
                    'assigned_at' => now(),
                ]);

                $bestRoom->available_slots--;
                $assignedCount++;
            }
        });

        $this->refreshStats();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Proceso Terminado', 'text' => "Se distribuyeron {$assignedCount} postulantes."]);
    }

    // --- ACCIÓN: RESETEAR ---
    public function resetDistribution()
    {
        ExamClassroomAssignment::truncate();
        $this->refreshStats();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Reseteado', 'text' => 'Todas las asignaciones han sido eliminadas.']);
    }

    // --- RENDER ---
    public function render()
    {
        // Datos para los selects
        $modalities = AdmissionModality::where('is_active', true)->get();
        $careers = Career::where('status', 'active')->get();
        $shifts = Shift::all();

        // Datos para las tarjetas de aulas (Panel inferior)
        $classrooms = ExamClassroom::with('pavilion')
            ->withCount('assignments')
            ->orderBy('exam_pavilion_id')
            ->orderBy('room_number')
            ->get();

        return view('livewire.pages.admission.exam.distribution-manager', compact('modalities', 'careers', 'shifts', 'classrooms'));
    }
}
