<?php

namespace App\Livewire\Pages\AcademicProcess\AcademicPeriods;

use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\Institution;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AcademicPeriodManager extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    // --- Formulario ---
    public $academic_year_id = '';
    public $code = ''; // Ej. 2025-I
    public $name = '';

    // Fechas Principales
    public $start_date = '';
    public $end_date = '';

    // Fechas Procesos
    public $enrollment_start_date = '';
    public $enrollment_end_date = '';
    public $classes_start_date = '';
    public $classes_end_date = '';
    public $grade_entry_start_date = ''; // Nuevo según migración
    public $grade_entry_end_date = '';   // Nuevo según migración

    public $status = 'planned';

    // --- Estado ---
    public ?AcademicPeriod $editingPeriod = null;
    public $isModalOpen = false;
    public $search = '';

    public function mount()
    {
        // Opcional: Cargar año actual por defecto
    }

    public function rules()
    {
        // Obtenemos ID de la institución del usuario actual (o la primera activa)
        $institutionId = Institution::where('status', 'active')->value('id');

        return [
            'academic_year_id' => 'required|exists:academic_years,id',
            'code' => [
                'required',
                'string',
                'max:20',
                // El código (2025-I) debe ser único por institución
                Rule::unique('academic_periods')->where('institution_id', $institutionId)->ignore($this->editingPeriod?->id)
            ],
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',

            // Validaciones lógicas de cronograma
            'enrollment_start_date' => 'required|date',
            'enrollment_end_date' => 'required|date|after_or_equal:enrollment_start_date',

            'classes_start_date' => 'required|date',
            'classes_end_date' => 'required|date|after:classes_start_date',

            'grade_entry_start_date' => 'nullable|date',
            'grade_entry_end_date' => 'nullable|date|after_or_equal:grade_entry_start_date',

            'status' => 'required|in:planned,active,closed',
        ];
    }

    public function create()
    {
        $this->authorize('gestionar-periodos');
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function edit(AcademicPeriod $period)
    {
        $this->authorize('gestionar-periodos');
        $this->editingPeriod = $period;

        $this->academic_year_id = $period->academic_year_id;
        $this->code = $period->code;
        $this->name = $period->name;

        // Formatear fechas para input date
        $this->start_date = $period->start_date->format('Y-m-d');
        $this->end_date = $period->end_date->format('Y-m-d');
        $this->enrollment_start_date = $period->enrollment_start_date->format('Y-m-d');
        $this->enrollment_end_date = $period->enrollment_end_date->format('Y-m-d');
        $this->classes_start_date = $period->classes_start_date->format('Y-m-d');
        $this->classes_end_date = $period->classes_end_date->format('Y-m-d');

        $this->grade_entry_start_date = $period->grade_entry_start_date?->format('Y-m-d');
        $this->grade_entry_end_date = $period->grade_entry_end_date?->format('Y-m-d');

        $this->status = $period->status;

        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->authorize('gestionar-periodos');
        $validated = $this->validate();

        // Inyectar institución ID
        $institutionId = Institution::where('status', 'active')->value('id');
        $validated['institution_id'] = $institutionId;

        // Si se activa este periodo, opcionalmente podríamos desactivar otros,
        // pero en IEST a veces hay solapamiento de recuperaciones, así que lo dejamos manual.

        if ($this->editingPeriod) {
            $this->editingPeriod->update($validated);
            $msg = 'Periodo actualizado.';
        } else {
            AcademicPeriod::create($validated);
            $msg = 'Periodo creado.';
        }

        $this->isModalOpen = false;
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Éxito', 'text' => $msg]);
    }

    public function confirmDelete($id)
    {
        $this->authorize('gestionar-periodos');
        $this->dispatch('swal:confirm', [
            'title' => '¿Eliminar Periodo?',
            'text' => 'No podrá eliminarlo si ya tiene matrículas o notas registradas.',
            'id' => $id,
            'method' => 'deletePeriod'
        ]);
    }

    #[On('deletePeriod')]
    public function deletePeriod($id)
    {
        try {
            AcademicPeriod::findOrFail($id)->delete();
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado', 'text' => 'Periodo eliminado.']);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Tiene registros asociados.']);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInput()
    {
        $this->editingPeriod = null;
        $this->code = '';
        $this->name = '';
        $this->status = 'planned';
        $this->start_date = '';
        $this->end_date = '';
        $this->enrollment_start_date = '';
        $this->enrollment_end_date = '';
        $this->classes_start_date = '';
        $this->classes_end_date = '';
        $this->grade_entry_start_date = '';
        $this->grade_entry_end_date = '';
    }

    public function render()
    {
        $years = AcademicYear::orderBy('year', 'desc')->get();

        $query = AcademicPeriod::with('academicYear')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('code', 'like', "%{$this->search}%");
            });

        return view('livewire.pages.academic-process.academic-periods.academic-period-manager', [
            'periods' => $query->orderBy('start_date', 'desc')->paginate(10),
            'years' => $years
        ]);
    }
}
