<?php

namespace App\Livewire\Pages\Settings\Shifts;

use App\Models\Shift;
use Illuminate\Database\QueryException;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ShiftManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $name = '';
    public $description = '';
    public $start_time = '';
    public $end_time = '';
    public $status = 'active';

    // --- PROPIEDADES DE ESTADO ---
    public ?Shift $editingShift = null;
    public $isModalOpen = false;
    public $search = '';

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'description' => 'nullable|string|max:255',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:active,inactive',
            // Regla: El 'name' debe ser único
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('shifts')->ignore($this->editingShift?->id)
            ],
        ];
    }
    
    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(Shift $shift)
    {
        $this->editingShift = $shift;
        $this->fill($shift->only('name', 'description', 'status'));
        // Formatear la hora para el input time
        $this->start_time = $shift->start_time->format('H:i');
        $this->end_time = $shift->end_time->format('H:i');
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function save()
    {
        $data = $this->validate();
        
        $model = $this->editingShift ?? new Shift();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Turno guardado correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Turno?',
            'text' => 'Esto eliminará el turno. Asegúrese de que no esté en uso.',
            'onConfirmed' => 'deleteShift'
        ]);
    }

    #[On('deleteShift')]
    public function deleteShift(int $id)
    {
        try {
            Shift::findOrFail($id)->delete();
            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Eliminado!',
                'text' => 'El turno ha sido eliminado.',
            ]);
        } catch (QueryException $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al eliminar',
                'text' => 'No se puede eliminar, es probable que esté asociado a horarios o secciones.',
                'toast' => false, 'position' => 'center', 'timer' => null, 'showConfirmButton' => true,
            ]);
        }
    }

    // --- RENDER ---
    public function render()
    {
        $query = Shift::query();

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }
        
        $shifts = $query->orderBy('start_time')->paginate(10);

        return view('livewire.pages.settings.shifts.shift-manager', [
            'shifts' => $shifts,
        ]);
    }
}