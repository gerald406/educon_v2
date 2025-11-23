<?php

namespace App\Livewire\Pages\Admission;

use App\Models\AcademicPeriod;
use App\Models\AdmissionOffering;
use App\Models\Career;
use App\Models\Shift;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AdmissionOfferingManager extends Component
{
    use WithPagination;

    public $activePeriod;
    public $career_id, $shift_id, $vacancies = 40, $is_active = true;
    public ?AdmissionOffering $editingOffering = null;
    public $isModalOpen = false;

    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
    }

    public function render()
    {
        $offerings = AdmissionOffering::with(['career', 'shift'])
            ->where('academic_period_id', $this->activePeriod?->id)
            ->paginate(10);

        return view('livewire.pages.admission.admission-offering-manager', [
            'offerings' => $offerings,
            'careers' => Career::where('status', 'active')->get(),
            'shifts' => Shift::all(),
        ]);
    }

    public function save()
    {
        $this->validate([
            'career_id' => 'required|exists:careers,id',
            'shift_id' => 'required|exists:shifts,id',
            'vacancies' => 'required|integer|min:1'
        ]);

        AdmissionOffering::updateOrCreate(
            ['id' => $this->editingOffering?->id],
            [
                'academic_period_id' => $this->activePeriod->id,
                'career_id' => $this->career_id,
                'shift_id' => $this->shift_id,
                'vacancies' => $this->vacancies,
                'is_active' => $this->is_active
            ]
        );

        $this->isModalOpen = false;
        $this->resetInput();
    }

    public function create()
    {
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function edit(AdmissionOffering $offering)
    {
        $this->editingOffering = $offering;
        $this->career_id = $offering->career_id;
        $this->shift_id = $offering->shift_id;
        $this->vacancies = $offering->vacancies;
        $this->is_active = $offering->is_active;
        $this->isModalOpen = true;
    }

    public function resetInput()
    {
        $this->reset('career_id', 'shift_id', 'vacancies', 'is_active', 'editingOffering');
    }
}
