<?php

namespace App\Livewire\Pages\Admission;

use App\Models\OriginSchool;
use App\Models\Location; // Asegúrate de tener este modelo
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
class OriginSchoolManager extends Component
{
    use WithPagination;

    // --- BÚSQUEDA ---
    public $search = '';

    // --- FORMULARIO ---
    public $modular_code = '';
    public $name = '';
    public $d_niv_mod = 'Secundaria'; // Valor por defecto
    public $management_type = 'Pública';
    public $ubigeo_code = null;

    // --- BÚSQUEDA DE UBIGEO (EN MODAL) ---
    public $ubigeoSearch = '';
    public $ubigeoResults = [];
    public $selectedUbigeoName = '';

    // --- ESTADO ---
    public ?OriginSchool $editingSchool = null;
    public $isModalOpen = false;

    // --- REGLAS DE VALIDACIÓN ---
    protected function rules()
    {
        return [
            'modular_code' => [
                'required',
                'string',
                'max:20',
                Rule::unique('origin_schools', 'modular_code')->ignore($this->editingSchool?->id),
            ],
            'name' => 'required|string|max:255',
            'd_niv_mod' => 'required|string|max:255',
            'management_type' => 'nullable|string|in:Pública,Privada',
            'ubigeo_code' => 'required|exists:locations,iddist', // Valida contra tu tabla locations
        ];
    }

    protected $messages = [
        'ubigeo_code.required' => 'Debe seleccionar un distrito (Ubigeo).',
        'ubigeo_code.exists' => 'El código de ubigeo seleccionado no es válido.',
        'modular_code.unique' => 'El código modular ya está registrado.',
    ];

    public function render()
    {
        $schools = OriginSchool::query()
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('modular_code', 'like', '%' . $this->search . '%');
            })
            // Asumimos que quieres ver la ubicación (Location) en la lista
            // Asegúrate de tener la relación en tu modelo OriginSchool: public function location() { return $this->belongsTo(Location::class, 'ubigeo_code', 'iddist'); }
            ->orderBy('name')
            ->paginate(10);

        return view('livewire.pages.admission.origin-school-manager', [
            'schools' => $schools
        ]);
    }

    // --- LÓGICA DE UBIGEO ---
    public function updatedUbigeoSearch($value)
    {
        if (strlen($value) < 3) {
            $this->ubigeoResults = [];
            return;
        }

        // Búsqueda por Distrito o Provincia
        $this->ubigeoResults = Location::where('nombdist', 'like', "%$value%")
            ->orWhere('nombprov', 'like', "%$value%")
            ->take(10)
            ->get();
    }

    public function selectUbigeo($id, $name)
    {
        $this->ubigeo_code = $id;
        $this->selectedUbigeoName = $name; // Ej: Puno - Puno - Puno
        $this->ubigeoSearch = '';
        $this->ubigeoResults = [];
    }

    // --- CRUD ---

    public function create()
    {
        $this->resetInput();
        $this->isModalOpen = true;
    }

    public function edit(OriginSchool $school)
    {
        $this->editingSchool = $school;
        $this->modular_code = $school->modular_code;
        $this->name = $school->name;
        $this->d_niv_mod = $school->d_niv_mod;
        $this->management_type = $school->management_type;
        $this->ubigeo_code = $school->ubigeo_code;

        // Recuperar nombre del Ubigeo para mostrarlo
        // Asumiendo que tienes un modelo Location con campos nombdist, nombprov, nombdep
        if ($school->ubigeo_code) {
            $location = Location::where('iddist', $school->ubigeo_code)->first();
            if ($location) {
                $this->selectedUbigeoName = "{$location->nombdep} - {$location->nombprov} - {$location->nombdist}";
            }
        }

        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        OriginSchool::updateOrCreate(
            ['id' => $this->editingSchool?->id],
            [
                'modular_code' => $this->modular_code,
                'name' => $this->name,
                'd_niv_mod' => $this->d_niv_mod,
                'management_type' => $this->management_type,
                'ubigeo_code' => $this->ubigeo_code,
            ]
        );

        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Guardado!',
            'text' => 'El colegio ha sido registrado correctamente.'
        ]);

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Colegio?',
            'text' => 'Esta acción no se puede deshacer.',
            'onConfirmed' => 'deleteSchool'
        ]);
    }

    #[\Livewire\Attributes\On('deleteSchool')]
    public function deleteSchool($id)
    {
        OriginSchool::find($id)?->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado']);
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInput();
    }

    public function resetInput()
    {
        $this->reset(
            'modular_code',
            'name',
            'd_niv_mod',
            'management_type',
            'ubigeo_code',
            'ubigeoSearch',
            'ubigeoResults',
            'selectedUbigeoName',
            'editingSchool'
        );
        $this->resetErrorBag();
    }
}
