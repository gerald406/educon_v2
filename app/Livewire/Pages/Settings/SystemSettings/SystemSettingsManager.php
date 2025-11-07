<?php

namespace App\Livewire\Pages\Settings\SystemSettings;

use App\Models\SystemSetting;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SystemSettingsManager extends Component
{
    // Colección de todos los settings
    public Collection $settings;

    // Array para el data binding (wire:model)
    // Tendrá la forma: ['minimum_passing_grade' => '13', 'institutional_email_domain' => '...']
    public $values = [];

    // Reglas de validación
    protected $rules = [
        'values.minimum_passing_grade' => 'required|integer|min:0|max:20',
        'values.minimum_attendance_percentage' => 'required|integer|min:0|max:100',
        'values.institutional_email_domain' => 'required|string|starts_with:@',
        // Se pueden añadir más reglas para futuras llaves
    ];

    // Mensajes de error personalizados
    protected $validationAttributes = [
        'values.minimum_passing_grade' => 'Nota Mínima Aprobatoria',
        'values.minimum_attendance_percentage' => 'Porcentaje Mínimo de Asistencia',
        'values.institutional_email_domain' => 'Dominio de Email Institucional',
    ];

    /**
     * Hook 'mount': Carga las configuraciones.
     */
    public function mount()
    {
        $this->loadSettings();
    }

    /**
     * Carga los settings de la BD y los mapea al array 'values'.
     */
    public function loadSettings()
    {
        // Cargamos solo los editables
        $this->settings = SystemSetting::where('is_editable', true)
                            ->orderBy('module')
                            ->orderBy('key_name')
                            ->get();

        // Mapeamos a nuestro array de valores usando el 'key_name' como índice
        $this->values = $this->settings->pluck('value', 'key_name')->toArray();
    }

    /**
     * Guarda todas las configuraciones.
     */
    public function save()
    {
        $this->validate();

        try {
            foreach ($this->values as $key => $value) {
                SystemSetting::where('key_name', $key)->update(['value' => $value]);
            }

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Hecho!',
                'text' => 'Configuraciones guardadas correctamente.',
            ]);
            
            // Recargamos los datos por si acaso
            $this->loadSettings();

        } catch (\Exception $e) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo guardar la configuración.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.pages.settings.system-settings.system-settings-manager');
    }
}