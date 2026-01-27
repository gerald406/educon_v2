<?php

namespace App\Livewire\Pages\Academic\Careers;

use App\Models\Career;
use App\Models\Institution;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
#[Title('Gestión de Programas de Estudio')]
class CareerManager extends Component
{
    use WithPagination;
    use AuthorizesRequests; // Habilita $this->authorize()

    // --- PROPIEDADES DEL FORMULARIO ---
    public int|string $institution_id = '';
    public string $code = '';
    public string $name = '';
    public int $duration_semesters = 6;
    public string $degree_awarded = '';
    public string $authorization_resolution = '';
    public string $status = 'active';

    // --- ESTADO Y UI ---
    public ?Career $editingCareer = null;
    public bool $isModalOpen = false;
    public string $search = '';

    // --- COLECCIONES (Optimización) ---
    public Collection $institutions;

    public function mount()
    {
        // Cargamos solo ID y Nombre para optimizar memoria
        $this->institutions = Institution::where('status', 'active')->pluck('name', 'id');

        // UX: Pre-seleccionar la primera institución si existe
        if ($this->institutions->isNotEmpty()) {
            $this->institution_id = $this->institutions->keys()->first();
        }
    }

    // --- REGLAS DE VALIDACIÓN ---
    public function rules()
    {
        return [
            'institution_id' => ['required', 'exists:institutions,id'],
            'code' => [
                'required',
                'string',
                'max:10',
                // Validación compuesta: El código debe ser único SOLO en esta institución
                Rule::unique('careers')
                    ->where('institution_id', $this->institution_id)
                    ->ignore($this->editingCareer?->id),
            ],
            'name' => ['required', 'string', 'max:150'],
            'duration_semesters' => ['required', 'integer', 'min:2', 'max:10'],
            'degree_awarded' => ['nullable', 'string', 'max:200'],
            'authorization_resolution' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function validationAttributes()
    {
        return [
            'institution_id' => 'institución',
            'code' => 'código',
            'duration_semesters' => 'duración',
        ];
    }

    // --- ACCIONES ---

    public function create()
    {
        $this->authorize('gestionar-estructura-academica');
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function edit(Career $career)
    {
        $this->authorize('gestionar-estructura-academica');
        $this->editingCareer = $career;

        $this->institution_id = $career->institution_id;
        $this->code = $career->code;
        $this->name = $career->name;
        $this->duration_semesters = $career->duration_semesters;
        $this->degree_awarded = $career->degree_awarded;
        $this->authorization_resolution = $career->authorization_resolution;
        $this->status = $career->status;

        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->authorize('gestionar-estructura-academica');
        $validated = $this->validate();

        try {
            if ($this->editingCareer) {
                $this->editingCareer->update($validated);
                $message = 'Programa actualizado correctamente.';
            } else {
                Career::create($validated);
                $message = 'Programa creado correctamente.';
            }

            $this->isModalOpen = false;

            // SweetAlert (Evento global)
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Éxito',
                'text' => $message,
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'Ocurrió un error inesperado: ' . $e->getMessage(),
            ]);
        }
    }

    // --- ELIMINACIÓN SEGURA ---

    public function confirmDelete($id)
    {
        $this->authorize('gestionar-estructura-academica');
        // Dispara el SweetAlert de confirmación en el frontend
        $this->dispatch('swal:confirm', [
            'title' => '¿Eliminar Programa?',
            'text' => 'Al eliminarlo, se perderán los planes de estudio asociados.',
            'id' => $id,
            'method' => 'deleteCareer' // Método que Livewire llamará si se confirma
        ]);
    }

    #[On('deleteCareer')]
    public function deleteCareer($id)
    {
        $this->authorize('gestionar-estructura-academica');
        try {
            $career = Career::findOrFail($id);
            $career->delete();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'El programa ha sido eliminado.',
            ]);
        } catch (QueryException $e) {
            // Manejo de integridad referencial (si ya tiene alumnos matriculados)
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'No se puede eliminar',
                'text' => 'El programa tiene registros asociados (planes o estudiantes) que impiden su eliminación.',
            ]);
        }
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetValidation();
    }

    private function resetInput()
    {
        $this->editingCareer = null;
        $this->code = '';
        $this->name = '';
        $this->degree_awarded = '';
        $this->authorization_resolution = '';
        $this->status = 'active';
        // Mantenemos la institution_id seleccionada para agilizar cargas masivas
    }

    public function render()
    {
        $query = Career::with('institution')
            ->when($this->institution_id, fn($q) => $q->where('institution_id', $this->institution_id))
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
            });
            });

        return view('livewire.pages.academic.careers.career-manager', [
            'careers' => $query->orderBy('name')->paginate(10)
        ]);
    }
}
