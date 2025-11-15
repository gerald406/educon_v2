<?php

namespace App\Livewire\Pages\Treasury; // <-- [NAMESPACE CORREGIDO]

use App\Models\Institution;
use App\Models\VoucherSeries;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class VoucherSeriesManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $institution_id = '';
    public $voucher_type = 'recibo';
    public $series = '';
    public $current_number = 0;
    public $status = 'active';

    // --- PROPIEDADES DE ESTADO ---
    public ?VoucherSeries $editingSeries = null;
    public $isModalOpen = false;
    public $search = '';

    public function mount()
    {
        $this->institution_id = Institution::first()->id;
    }

    protected function rules()
    {
        return [
            'institution_id' => 'required|exists:institutions,id',
            'voucher_type' => 'required|in:boleta,factura,nota_credito,recibo',
            'series' => [
                'required',
                'string',
                'max:8',
                Rule::unique('voucher_series')->where(
                    fn($query) =>
                    $query->where('institution_id', $this->institution_id)
                        ->where('voucher_type', $this->voucher_type)
                )->ignore($this->editingSeries?->id)
            ],
            'current_number' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected $messages = [
        'series.unique' => 'La combinación de Institución, Tipo y Serie ya existe.',
    ];

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(VoucherSeries $series)
    {
        $this->editingSeries = $series;
        $this->fill($series);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset('voucher_type', 'series', 'current_number', 'status', 'editingSeries');
        $this->voucher_type = 'recibo';
        $this->current_number = 0;
        $this->status = 'active';
        $this->resetValidation();
    }

    public function save()
    {
        $data = $this->validate();

        $model = $this->editingSeries ?? new VoucherSeries();
        $model->fill($data);
        $model->save();

        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Serie de comprobante guardada correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this-> dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Serie?',
            'onConfirmed' => 'deleteSeries'
        ]);
    }

    #[On('deleteSeries')]
    public function deleteSeries(int $id)
    {
        VoucherSeries::findOrFail($id)->delete();
        $this-> dispatch('swal', ['icon' => 'success', 'title' => '¡Eliminado!']);
    }

    // --- RENDER ---
    public function render()
    {
        $query = VoucherSeries::query();

        if ($this->search) {
            $query->where('series', 'like', '%' . $this->search . '%')
                ->orWhere('voucher_type', 'like', '%' . $this->search . '%');
        }

        $seriesList = $query->orderBy('voucher_type')->orderBy('series')->paginate(10);

        // [VISTA CORREGIDA]
        return view('livewire.pages.treasury.voucher-series-manager', [
            'seriesList' => $seriesList,
        ]);
    }
}
