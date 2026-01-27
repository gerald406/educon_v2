<?php

namespace App\Livewire\Pages\Academic\StudyPlans;

use App\Models\Career;
use App\Models\StudyPlan;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class StudyPlanManager extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    // --- Propiedades ---
    public $career_id = '';
    public $code = '';
    public $name = '';
    public $version = '';
    public $start_date = '';
    public $end_date = null;
    public $total_credits = 0;
    public $total_hours = 0;
    public $approval_resolution = '';
    public $status = 'active';

    // --- Estado ---
    public ?StudyPlan $editingStudyPlan = null;
    public $isModalOpen = false;
    public $search = '';

    public Collection $careers;

    public function mount()
    {
        $this->careers = Career::where('status', 'active')->orderBy('name')->pluck('name', 'id');

        // Preselección inteligente
        if (!$this->editingStudyPlan && $this->careers->count() > 0) {
            $this->career_id = $this->careers->keys()->first();
        }
    }

    public function rules()
    {
        return [
            'career_id' => ['required', 'exists:careers,id'],
            'code' => [
                'required',
                'string',
                'max:20',
                // El código debe ser único para LA MISMA carrera
                Rule::unique('study_plans')
                    ->where('career_id', $this->career_id)
                    ->ignore($this->editingStudyPlan?->id)
            ],
            'name' => ['required', 'string', 'max:100'],
            'version' => ['required', 'string', 'max:10'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after:start_date'],
            'total_credits' => ['required', 'integer', 'min:1'],
            'total_hours' => ['required', 'integer', 'min:1'],
            'approval_resolution' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,inactive,obsolete'],
        ];
    }

    // --- Acciones ---

    public function create()
    {
        $this->authorize('gestionar-estructura-academica');
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function edit(StudyPlan $plan)
    {
        $this->authorize('gestionar-estructura-academica');
        $this->editingStudyPlan = $plan;

        $this->career_id = $plan->career_id;
        $this->code = $plan->code;
        $this->name = $plan->name;
        $this->version = $plan->version;
        $this->start_date = $plan->start_date->format('Y-m-d');
        $this->end_date = $plan->end_date ? $plan->end_date->format('Y-m-d') : null;
        $this->total_credits = $plan->total_credits;
        $this->total_hours = $plan->total_hours;
        $this->approval_resolution = $plan->approval_resolution;
        $this->status = $plan->status;

        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->authorize('gestionar-estructura-academica');
        $validated = $this->validate();

        if (empty($validated['end_date'])) {
            $validated['end_date'] = null;
        }

        try {
            if ($this->editingStudyPlan) {
                $this->editingStudyPlan->update($validated);
                $msg = 'Plan de estudios actualizado.';
            } else {
                StudyPlan::create($validated);
                $msg = 'Plan de estudios registrado.';
            }

            $this->isModalOpen = false;
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Éxito', 'text' => $msg]);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function confirmDelete($id)
    {
        $this->authorize('gestionar-estructura-academica');
        $this->dispatch('swal:confirm', [
            'title' => '¿Eliminar Plan?',
            'text' => 'Se eliminarán módulos y unidades asociadas. No reversible.',
            'id' => $id,
            'method' => 'deleteStudyPlan'
        ]);
    }

    #[On('deleteStudyPlan')]
    public function deleteStudyPlan($id)
    {
        $this->authorize('gestionar-estructura-academica');
        try {
            StudyPlan::findOrFail($id)->delete();
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Plan eliminado.']);
        } catch (QueryException $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Tiene registros asociados imposibles de borrar.']);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    private function resetInput()
    {
        $this->editingStudyPlan = null;
        $this->code = '';
        $this->name = '';
        $this->version = '';
        $this->start_date = '';
        $this->end_date = null;
        $this->total_credits = 0;
        $this->total_hours = 0;
        $this->approval_resolution = '';
        $this->status = 'active';
    }

    public function render()
    {
        $query = StudyPlan::with('career')
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%')
                    ->orWhereHas('career', fn($c) => $c->where('name', 'like', '%' . $this->search . '%'));
            });
            });

        return view('livewire.pages.academic.study-plans.study-plan-manager', [
            'plans' => $query->orderBy('name')->paginate(10)
        ]);
    }
}
