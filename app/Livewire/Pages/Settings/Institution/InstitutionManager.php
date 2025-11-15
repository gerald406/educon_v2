<?php

namespace App\Livewire\Pages\Settings\Institution;

use App\Models\Institution;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads; // Para subir el logo
use Illuminate\Support\Facades\Storage;

#[Layout('layouts.app')]
class InstitutionManager extends Component
{
    use WithFileUploads;

    public ?Institution $institution;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $code = '';
    public $name = '';
    public $tax_id = ''; // RUC
    public $address = '';
    public $phone = '';
    public $email = '';
    public $website = '';
    public $logo_url = '';
    public $status = 'active';

    // Para la subida de archivos
    public $logoUpload; // Propiedad temporal para el archivo

    /**
     * Hook 'mount': Carga la institución principal.
     */
    public function mount()
    {
        // Buscamos la institución creada por el seeder
        $this->institution = Institution::first();

        if ($this->institution) {
            $this->fill($this->institution->only(
                'code', 'name', 'tax_id', 'address', 'phone', 
                'email', 'website', 'logo_url', 'status'
            ));
        } else {
            // Manejo de error si no se encuentra la institución
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error Crítico',
                'text' => 'No se encontró la institución principal. Contacte a soporte.',
            ]);
        }
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'name' => 'required|string|max:200',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'website' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
            'logoUpload' => 'nullable|image|max:2048', // 2MB Max
            // Reglas 'unique' que ignoran el ID actual
            'code' => [
                'required',
                'string',
                'max:10',
                Rule::unique('institutions')->ignore($this->institution->id)
            ],
            'tax_id' => [
                'required',
                'string',
                'max:11',
                Rule::unique('institutions')->ignore($this->institution->id)
            ],
        ];
    }
    
    /**
     * Guarda los cambios en la institución.
     */
    public function save()
    {
        $data = $this->validate();

        if (!$this->institution) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'Institución no encontrada.']);
            return;
        }

        try {
            // Manejo de la subida del logo
            if ($this->logoUpload) {
                // Borra el logo anterior si existe
                if ($this->institution->logo_url) {
                    // \Storage::disk('public')->delete($this->institution->logo_url);
                    Storage::disk('public')->delete($this->institution->logo_url);
                }
                // Guarda el nuevo logo en 'storage/app/public/logos'
                $path = $this->logoUpload->store('logos', 'public');
                $data['logo_url'] = $path;
            }

            $this->institution->update($data);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Hecho!',
                'text' => 'Datos de la institución actualizados.',
            ]);
            
            // Recargar los datos (especialmente la URL del logo)
            $this->mount();

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error al guardar',
                'text' => $e->getMessage(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.pages.settings.institution.institution-manager');
    }
}