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
use Livewire\WithPagination;

#[Layout('layouts.app')]
class DistributionManager extends Component
{
    use WithPagination;

    // --- FILTROS ---
    public $filterModality = '';
    public $filterCareer = '';
    public $filterShift = '';

    // --- SELECCIÓN MANUAL ---
    public $selectedApplicants = [];
    public $selectAll = false;
    public $targetClassroom = '';

    // --- ESTADO ---
    public $distributedCount = 0;
    public $totalCapacity = 0;
    public $usedCapacity = 0;

    public function mount()
    {
        $this->refreshStats();
    }

    public function updatedFilterModality()
    {
        $this->resetPage();
        $this->selectedApplicants = [];
        $this->selectAll = false;
    }
    public function updatedFilterCareer()
    {
        $this->resetPage();
        $this->selectedApplicants = [];
        $this->selectAll = false;
    }
    public function updatedFilterShift()
    {
        $this->resetPage();
        $this->selectedApplicants = [];
        $this->selectAll = false;
    }

    public function refreshStats()
    {
        $this->distributedCount = ExamClassroomAssignment::count();
        $this->totalCapacity = ExamClassroom::where('is_active', true)->sum('capacity');
        $this->usedCapacity = $this->distributedCount;
    }

    // --- LÓGICA DE SELECCIÓN ---
    public function updatedSelectAll($value)
    {
        if ($value) {
            // Selecciona todos los IDs de la PÁGINA ACTUAL
            $this->selectedApplicants = $this->getApplicantsQuery()
                ->paginate(10)
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedApplicants = [];
        }
    }

    private function getApplicantsQuery()
    {
        // [CORRECCIÓN CRÍTICA]
        // 1. Buscamos 'registrado' en español.
        // 2. Agregamos ->has('user') para evitar error 500 si se borró el usuario.

        $query = Applicant::with(['user', 'admissionOffering.career', 'admissionModality'])
            ->has('user')
            ->where('application_status', 'registrado') // <--- CAMBIO AQUÍ
            ->whereDoesntHave('examAssignment');

        if ($this->filterModality) $query->where('admission_modality_id', $this->filterModality);

        if ($this->filterCareer) {
            $query->whereHas('admissionOffering', fn($q) => $q->where('career_id', $this->filterCareer));
        }

        if ($this->filterShift) {
            $query->whereHas('admissionOffering', fn($q) => $q->where('shift_id', $this->filterShift));
        }

        return $query;
    }

    // --- ACCIÓN: ASIGNACIÓN MANUAL ---
    public function assignManual()
    {
        $this->validate([
            'targetClassroom' => 'required|exists:exam_classrooms,id',
            'selectedApplicants' => 'required|array|min:1'
        ], [
            'targetClassroom.required' => 'Debe seleccionar un aula destino.',
            'selectedApplicants.required' => 'Debe marcar al menos un postulante.'
        ]);

        $classroom = ExamClassroom::withCount('assignments')->find($this->targetClassroom);
        $availableSlots = $classroom->capacity - $classroom->assignments_count;
        $countToAssign = count($this->selectedApplicants);

        if ($countToAssign > $availableSlots) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Espacio Insuficiente',
                'text' => "Intenta asignar $countToAssign postulantes, pero el aula solo tiene $availableSlots espacios libres."
            ]);
            return;
        }

        DB::transaction(function () use ($classroom) {
            foreach ($this->selectedApplicants as $applicantId) {
                // Verificar doble asignación por seguridad
                $exists = ExamClassroomAssignment::where('applicant_id', $applicantId)->exists();
                if (!$exists) {
                    ExamClassroomAssignment::create([
                        'exam_classroom_id' => $classroom->id,
                        'applicant_id' => $applicantId,
                        'assigned_at' => now(),
                    ]);
                }
            }
        });

        $this->selectedApplicants = [];
        $this->selectAll = false;
        $this->targetClassroom = '';
        $this->refreshStats();

        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Asignados', 'text' => 'Postulantes distribuidos correctamente.']);
    }

    // --- ACCIÓN: DISTRIBUCIÓN AUTOMÁTICA ---
    public function autoDistribute()
    {
        // 1. Obtener postulantes filtrados usando la misma lógica
        $applicants = $this->getApplicantsQuery()->get();

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

        // 3. Algoritmo
        $applicants = $applicants->shuffle();
        $assignedCount = 0;

        DB::transaction(function () use ($applicants, $classrooms, &$assignedCount) {
            foreach ($applicants as $applicant) {
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

    public function resetDistribution()
    {
        ExamClassroomAssignment::truncate();
        $this->refreshStats();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Reseteado', 'text' => 'Todas las asignaciones han sido eliminadas.']);
    }

    public function render()
    {
        $modalities = AdmissionModality::where('is_active', true)->get();
        $careers = Career::where('status', 'active')->get();
        $shifts = Shift::all();

        // Aulas para el select manual (solo las que tienen espacio)
        $availableClassrooms = ExamClassroom::with('pavilion')
            ->where('is_active', true)
            ->withCount('assignments')
            ->get()
            ->filter(fn($c) => ($c->capacity - $c->assignments_count) > 0);

        // Listado paginado
        $applicants = $this->getApplicantsQuery()->paginate(10);

        // Estado general de aulas
        $classroomsStatus = ExamClassroom::with('pavilion')
            ->withCount('assignments')
            ->orderBy('exam_pavilion_id')
            ->orderBy('room_number')
            ->get();

        return view('livewire.pages.admission.exam.distribution-manager', [
            'modalities' => $modalities,
            'careers' => $careers,
            'shifts' => $shifts,
            'applicants' => $applicants,
            'availableClassrooms' => $availableClassrooms,
            'classroomsStatus' => $classroomsStatus
        ]);
    }
}
