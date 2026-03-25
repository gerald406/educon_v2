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
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class DistributionManager extends Component
{
    use WithPagination;

    // --- FILTROS ---
    public $filterModality = '';
    public $filterCareer   = '';
    public $filterShift    = '';
    public $search         = '';
    public $perPage        = 25;

    // --- SELECCIÓN MANUAL ---
    public $selectedApplicants = [];
    public $selectAll          = false;
    public $selectAllMode      = false; // true = todos los filtrados, no solo la página
    public $targetClassroom    = '';

    // --- ESTADO ---
    public $distributedCount = 0;
    public $totalCapacity    = 0;
    public $usedCapacity     = 0;
    public $totalFiltered    = 0;

    public function mount(): void
    {
        $this->refreshStats();
    }

    // --- LIFECYCLE HOOKS DE FILTROS ---

    public function updatedFilterModality(): void { $this->resetFilters(); }
    public function updatedFilterCareer(): void   { $this->resetFilters(); }
    public function updatedFilterShift(): void    { $this->resetFilters(); }
    public function updatedPerPage(): void        { $this->resetFilters(); }

    public function updatedSearch(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    private function resetFilters(): void
    {
        $this->resetPage();
        $this->clearSelection();
    }

    public function clearSelection(): void
    {
        $this->selectedApplicants = [];
        $this->selectAll          = false;
        $this->selectAllMode      = false;
    }

    // --- STATS ---

    public function refreshStats(): void
    {
        $this->distributedCount = ExamClassroomAssignment::count();
        $this->totalCapacity    = ExamClassroom::where('is_active', true)->sum('capacity');
        $this->usedCapacity     = $this->distributedCount;
    }

    // --- SELECCIÓN ---

    /**
     * Al marcar "seleccionar página": carga los IDs de la página actual.
     * Al desmarcar: limpia todo incluyendo el modo "todos los filtrados".
     */
    public function updatedSelectAll(bool $value): void
    {
        $this->selectAllMode = false;

        if ($value) {
            $this->selectedApplicants = $this->getApplicantsQuery()
                ->paginate($this->perPage)
                ->pluck('id')
                ->map(fn($id) => (string) $id)
                ->toArray();
        } else {
            $this->selectedApplicants = [];
        }
    }

    /**
     * Activa el modo "todos los filtrados": el assignment usará la query
     * completa en lugar del array de IDs, evitando cargar miles de IDs en memoria.
     */
    public function activateSelectAllMode(): void
    {
        $this->selectAllMode      = true;
        $this->selectedApplicants = [];
        $this->selectAll          = true;
    }

    // --- QUERY BASE ---

    private function getApplicantsQuery()
    {
        $query = Applicant::with(['user', 'admissionOffering.career', 'admissionOffering.shift', 'admissionModality'])
            ->has('user')
            ->where('application_status', 'registrado')
            ->whereDoesntHave('examAssignment');

        if ($this->filterModality) {
            $query->where('admission_modality_id', $this->filterModality);
        }

        if ($this->filterCareer) {
            $query->whereHas('admissionOffering', fn($q) => $q->where('career_id', $this->filterCareer));
        }

        if ($this->filterShift) {
            $query->whereHas('admissionOffering', fn($q) => $q->where('shift_id', $this->filterShift));
        }

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->whereHas('user', function ($q) use ($term) {
                $q->where('document_number', 'like', $term)
                  ->orWhere('name', 'like', $term)
                  ->orWhere('lastname', 'like', $term);
            });
        }

        return $query->orderBy('id');
    }

    // --- ACCIÓN: ASIGNACIÓN MANUAL ---

    public function assignManual(): void
    {
        $this->validate([
            'targetClassroom' => 'required|exists:exam_classrooms,id',
        ], [
            'targetClassroom.required' => 'Debe seleccionar un aula destino.',
        ]);

        // Determinar IDs a asignar
        if ($this->selectAllMode) {
            $ids = $this->getApplicantsQuery()->pluck('id')->toArray();
        } else {
            $ids = $this->selectedApplicants;
        }

        if (empty($ids)) {
            $this->dispatch('swal', [
                'icon'  => 'warning',
                'title' => 'Sin selección',
                'text'  => 'Debe marcar al menos un postulante.',
            ]);
            return;
        }

        $classroom      = ExamClassroom::withCount('assignments')->find($this->targetClassroom);
        $availableSlots = $classroom->capacity - $classroom->assignments_count;
        $countToAssign  = count($ids);

        if ($countToAssign > $availableSlots) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Espacio Insuficiente',
                'text'  => "Intenta asignar {$countToAssign} postulantes, pero el aula solo tiene {$availableSlots} espacios libres.",
            ]);
            return;
        }

        $assigned = 0;
        DB::transaction(function () use ($ids, $classroom, &$assigned) {
            foreach ($ids as $applicantId) {
                $exists = ExamClassroomAssignment::where('applicant_id', $applicantId)->exists();
                if (!$exists) {
                    ExamClassroomAssignment::create([
                        'exam_classroom_id' => $classroom->id,
                        'applicant_id'      => $applicantId,
                        'assigned_at'       => now(),
                    ]);
                    $assigned++;
                }
            }
        });

        $this->clearSelection();
        $this->targetClassroom = '';
        $this->refreshStats();

        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => 'Asignados',
            'text'  => "{$assigned} postulante(s) asignados a {$classroom->pavilion->name} - {$classroom->room_number}.",
        ]);
    }

    // --- ACCIÓN: DISTRIBUCIÓN AUTOMÁTICA ---

    public function autoDistribute(): void
    {
        $applicants = $this->getApplicantsQuery()->get();

        if ($applicants->isEmpty()) {
            $this->dispatch('swal', [
                'icon'  => 'warning',
                'title' => 'Sin postulantes',
                'text'  => 'No hay postulantes pendientes con estos filtros.',
            ]);
            return;
        }

        $classrooms = ExamClassroom::where('is_active', true)
            ->withCount('assignments')
            ->get()
            ->map(fn($room) => tap($room, fn($r) => $r->available_slots = $r->capacity - $r->assignments_count))
            ->filter(fn($r) => $r->available_slots > 0)
            ->values();

        if ($classrooms->isEmpty()) {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Aulas Llenas',
                'text'  => 'No hay espacio físico disponible.',
            ]);
            return;
        }

        $applicants    = $applicants->shuffle();
        $assignedCount = 0;

        DB::transaction(function () use ($applicants, $classrooms, &$assignedCount) {
            foreach ($applicants as $applicant) {
                $classrooms = $classrooms->sortByDesc('available_slots')->values();
                $bestRoom   = $classrooms->first();

                if ($bestRoom->available_slots <= 0) break;

                ExamClassroomAssignment::create([
                    'exam_classroom_id' => $bestRoom->id,
                    'applicant_id'      => $applicant->id,
                    'assigned_at'       => now(),
                ]);

                $bestRoom->available_slots--;
                $assignedCount++;
            }
        });

        $this->refreshStats();
        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => 'Proceso Terminado',
            'text'  => "Se distribuyeron {$assignedCount} postulantes automáticamente.",
        ]);
    }

    // --- ACCIÓN: RESET ---

    public function resetDistribution(): void
    {
        ExamClassroomAssignment::truncate();
        $this->clearSelection();
        $this->refreshStats();
        $this->dispatch('swal', [
            'icon'  => 'success',
            'title' => 'Reseteado',
            'text'  => 'Todas las asignaciones han sido eliminadas.',
        ]);
    }

    // --- RENDER ---

    public function render()
    {
        $query               = $this->getApplicantsQuery();
        $this->totalFiltered = $query->count();
        $applicants          = $query->paginate($this->perPage);

        $modalities = AdmissionModality::where('is_active', true)->get();
        $careers    = Career::where('status', 'active')->orderBy('name')->get();
        $shifts     = Shift::all();

        $availableClassrooms = ExamClassroom::with('pavilion')
            ->where('is_active', true)
            ->withCount('assignments')
            ->get()
            ->filter(fn($c) => ($c->capacity - $c->assignments_count) > 0);

        $classroomsStatus = ExamClassroom::with('pavilion')
            ->withCount('assignments')
            ->orderBy('exam_pavilion_id')
            ->orderBy('room_number')
            ->get();

        return view('livewire.pages.admission.exam.distribution-manager', [
            'modalities'          => $modalities,
            'careers'             => $careers,
            'shifts'              => $shifts,
            'applicants'          => $applicants,
            'totalFiltered'       => $this->totalFiltered,
            'availableClassrooms' => $availableClassrooms,
            'classroomsStatus'    => $classroomsStatus,
        ]);
    }
}
