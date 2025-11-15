<?php

namespace App\Livewire\Pages\Settings\AcademicYears;

use App\Models\AcademicYear;
use App\Models\Institution;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AcademicYearManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $institution_id = null;
    public $year = '';
    public $name = '';
    public $start_date = '';
    public $end_date = '';
    public $status = 'planned';

    // --- PROPIEDADES DE ESTADO ---
    public ?AcademicYear $editingYear = null;
    public $isModalOpen = false;
    public $search = '';
    
    // Institución principal
    public ?Institution $institution;

    /**
     * Hook 'mount': Carga la institución principal.
     */
    public function mount()
    {
        // Asumimos que trabajamos con la primera institución
        $this->institution = Institution::first();
        if ($this->institution) {
            $this->institution_id = $this->institution->id;
        }
        $this->year = date('Y'); // Pone el año actual por defecto
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,active,closed',
            // Regla: El 'year' debe ser único para esta 'institution_id'
            'year' => [
                'required',
                'integer',
                'digits:4',
                Rule::unique('academic_years')
                    ->where(fn ($query) => $query->where('institution_id', $this->institution_id))
                    ->ignore($this->editingYear?->id)
            ],
        ];
    }
    
    /**
     * Hook: Si el año cambia, sugiere fechas
     */
    public function updatedYear($value)
    {
        if (strlen($value) === 4 && !$this->editingYear) {
            $this->name = 'Año Académico ' . $value;
            $this->start_date = $value . '-01-01';
            $this->end_date = $value . '-12-31';
        }
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(AcademicYear $year)
    {
        $this->editingYear = $year;
        $this->fill($year->only('year', 'name', 'status'));
        $this->start_date = $year->start_date->format('Y-m-d');
        $this->end_date = $year->end_date->format('Y-m-d');
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset('year', 'name', 'start_date', 'end_date', 'status', 'editingYear');
        $this->resetValidation();
        $this->year = date('Y'); // Resetea al año actual
    }

    public function save()
    {
        $data = $this->validate();
        // Asignamos la institución (que no está en el formulario)
        $data['institution_id'] = $this->institution_id;
        
        $model = $this->editingYear ?? new AcademicYear();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Año Académico guardado correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Año Académico?',
            'text' => 'Esto eliminará el año. Asegúrese de que no tenga periodos académicos asociados.',
            'onConfirmed' => 'deleteYear'
        ]);
    }

    #[On('deleteYear')]
    public function deleteYear(int $id)
    {
        try {
            AcademicYear::findOrFail($id)->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El año académico ha sido eliminado.',
            ]);
        } catch (QueryException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se puede eliminar, es probable que tenga periodos académicos (semestres) asociados.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $query = AcademicYear::where('institution_id', $this->institution_id);

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('year', 'like', '%' . $this->search . '%');
            });
        }
        
        $years = $query->orderBy('year', 'desc')->paginate(10);

        return view('livewire.pages.settings.academic-years.academic-year-manager', [
            'years' => $years,
        ]);
    }
}