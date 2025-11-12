<?php

namespace App\Livewire\Pages\AcademicProcess\AcademicPeriods;

use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AcademicPeriodManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $academic_year_id = '';
    public $code = '';
    public $name = '';
    public $start_date = '';
    public $end_date = '';
    public $enrollment_start_date = '';
    public $enrollment_end_date = '';
    public $classes_start_date = '';
    public $classes_end_date = '';
    // [NUEVAS PROPIEDADES]
    public $grade_entry_start_date = '';
    public $grade_entry_end_date = '';

    public $status = 'planned';

    // --- PROPIEDADES DE ESTADO ---
    public ?AcademicPeriod $editingPeriod = null;
    public $isModalOpen = false;
    public $search = '';

    // Colección para el dropdown de Años Académicos
    public Collection $academicYears;
    public $institution_id;

    /**
     * Hook 'mount': Carga los Años Académicos activos.
     */
    public function mount()
    {
        // Asumimos que trabajamos con la primera institución
        $this->institution_id = \App\Models\Institution::first()->id;
        
        $this->academicYears = AcademicYear::where('institution_id', $this->institution_id)
                                ->whereIn('status', ['active', 'planned'])
                                ->orderBy('year', 'desc')
                                ->pluck('name', 'id');

        // Asignar el primer año académico por defecto
        if (!$this->editingPeriod && $this->academicYears->count() > 0) {
            $this->academic_year_id = $this->academicYears->keys()->first();
        }
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'enrollment_start_date' => 'required|date',
            'enrollment_end_date' => 'required|date|after_or_equal:enrollment_start_date',
            'classes_start_date' => 'required|date',
            'classes_end_date' => 'required|date|after_or_equal:classes_start_date',
            // [NUEVAS REGLAS]
            'grade_entry_start_date' => 'nullable|date',
            'grade_entry_end_date' => 'nullable|date|after_or_equal:grade_entry_start_date',

            'status' => 'required|in:planned,active,closed',
            // Regla: El 'code' debe ser único para esta 'institution_id'
            'code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('academic_periods')
                    ->where(fn ($query) => $query->where('institution_id', $this->institution_id))
                    ->ignore($this->editingPeriod?->id)
            ],
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(AcademicPeriod $period)
    {
        $this->editingPeriod = $period;
        $this->fill($period->only(
            'academic_year_id', 'code', 'name', 'status'
        ));
        // Formatear fechas
        $this->start_date = $period->start_date->format('Y-m-d');
        $this->end_date = $period->end_date->format('Y-m-d');
        $this->enrollment_start_date = $period->enrollment_start_date->format('Y-m-d');
        $this->enrollment_end_date = $period->enrollment_end_date->format('Y-m-d');
        $this->classes_start_date = $period->classes_start_date->format('Y-m-d');
        $this->classes_end_date = $period->classes_end_date->format('Y-m-d');
        // [NUEVO] Formatear fechas de notas (usamos datetime-local)
        $this->grade_entry_start_date = $period->grade_entry_start_date?->format('Y-m-d\TH:i');
        $this->grade_entry_end_date = $period->grade_entry_end_date?->format('Y-m-d\TH:i');
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->resetExcept('academicYears', 'institution_id');
        $this->resetValidation();
        // Re-asignar el año por defecto
        if ($this->academicYears->count() > 0) {
            $this->academic_year_id = $this->academicYears->keys()->first();
        }
    }

    public function save()
    {
        $data = $this->validate();
        $data['institution_id'] = $this->institution_id;
        // [NUEVO] Convertir vacíos a null
        $data['grade_entry_start_date'] = $data['grade_entry_start_date'] === '' ? null : $data['grade_entry_start_date'];
        $data['grade_entry_end_date'] = $data['grade_entry_end_date'] === '' ? null : $data['grade_entry_end_date'];
        
        $model = $this->editingPeriod ?? new AcademicPeriod();
        
        // Lógica para asegurar un solo periodo activo
        if ($data['status'] == 'active') {
            AcademicPeriod::where('institution_id', $this->institution_id)
                        ->where('id', '!=', $model->id)
                        ->update(['status' => 'planned']);
        }
        
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Periodo Académico guardado correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Periodo?',
            'text' => 'Esto eliminará el periodo y toda su carga académica asociada.',
            'onConfirmed' => 'deletePeriod'
        ]);
    }

    #[On('deletePeriod')]
    public function deletePeriod(int $id)
    {
        try {
            AcademicPeriod::findOrFail($id)->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El periodo académico ha sido eliminado.',
            ]);
        } catch (QueryException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se puede eliminar, tiene carga académica o matrículas asociadas.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $query = AcademicPeriod::with('academicYear')
                    ->where('institution_id', $this->institution_id);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%');
            });
        }
        
        $periods = $query->orderBy('start_date', 'desc')->paginate(10);

        return view('livewire.pages.academic-process.academic-periods.academic-period-manager', [
            'periods' => $periods,
        ]);
    }
}