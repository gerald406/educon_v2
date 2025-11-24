<?php

namespace App\Livewire\Pages\Admission;

use App\Models\AdmissionModality;
use App\Models\AdmissionOffering;
use App\Models\Applicant;
use App\Models\FinancialEntity;
use App\Models\Location;
use App\Models\OriginSchool;
use App\Models\User;
use App\Services\PersonDataService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

// Usamos 'layouts.guest' porque es para público externo (sin sidebar)
#[Layout('layouts.guest')]
class ApplicantRegistrationWizard extends Component
{
    use WithFileUploads;

    // --- CONTROL DE PASOS ---
    public $currentStep = 1;

    // --- PASO 1: IDENTIFICACIÓN ---
    public $dni = '';
    public $searchMessage = '';

    // --- PASO 2: DATOS PERSONALES ---
    public $name = '';
    public $paternal_surname = '';
    public $maternal_surname = '';
    public $email = '';
    public $phone = '';
    public $address = '';
    public $gender = '';
    public $birthday = '';
    public $photo; // Archivo temporal
    public $is_new_user = true;

    // Ubigeo Nacimiento (Cascada)
    public $departments = [], $provinces = [], $districts = [];
    public $selectedDep = '', $selectedProv = '', $selectedDistId = '';

    // --- PASO 3: COLEGIO DE PROCEDENCIA ---
    public $schoolSearch = '';
    public $schoolResults = [];
    public $selectedSchoolId = null;
    public $selectedSchoolName = '';
    public $schoolYear = '';

    // Ubigeo Colegio (Cascada independiente)
    public $schoolDepartments = [], $schoolProvinces = [], $schoolDistricts = [];
    public $schoolSelectedDep = '', $schoolSelectedProv = '', $schoolSelectedDistId = '';

    // --- PASO 4: REGISTRO DE POSTULANTE ---
    public $offerings = [];
    public $modalities = [];
    public $financialEntities = [];

    public $selectedOfferingId = '';
    public $selectedModalityId = '';
    public $selectedFinancialEntityId = '';
    public $paymentCode = '';

    // Inyección de Servicio
    protected function personService()
    {
        return new PersonDataService();
    }

    public function mount()
    {
        // Cargar departamentos para los selectores
        $this->departments = Location::select('nombdep')->distinct()->orderBy('nombdep')->pluck('nombdep', 'nombdep');
        $this->schoolDepartments = $this->departments; // Reutilizamos la lista

        // Cargar catálogos para el Paso 4
        $this->loadCatalogs();
    }

    public function loadCatalogs()
    {
        $this->modalities = AdmissionModality::where('is_active', true)->get();
        $this->financialEntities = FinancialEntity::where('is_active', true)->get();

        // Cargar Oferta (Carrera + Turno) del periodo activo (podrías filtrar por periodo aquí)
        $this->offerings = AdmissionOffering::with(['career', 'shift'])
            ->where('is_active', true)
            ->get();
    }

    // ========================================================================
    // LÓGICA PASO 1: BÚSQUEDA
    // ========================================================================
    public function searchDni()
    {
        $this->validate(['dni' => 'required|digits:8']);
        $this->searchMessage = '';

        // 1. Buscar en BD Local
        $user = User::where('document_number', $this->dni)->first();

        if ($user) {
            $this->is_new_user = false;
            // Asumimos que 'lastname' guarda "Paterno Materno". Intentamos separarlos visualmente si es posible
            $this->name = $user->name;
            // Si tienes 'lastname' completo en BD, lo ponemos en paterno temporalmente
            $this->paternal_surname = $user->lastname;
            $this->email = $user->email;
            $this->searchMessage = "Usuario encontrado. Sus datos se han cargado.";
        } else {
            // 2. Buscar en API Externa
            $this->is_new_user = true;
            $apiData = $this->personService()->search($this->dni);

            if ($apiData) {
                $this->name = $apiData['nombres'];
                $this->paternal_surname = $apiData['apellido_paterno'];
                $this->maternal_surname = $apiData['apellido_materno'];
                $this->searchMessage = "Datos recuperados de RENIEC/API.";
            } else {
                $this->searchMessage = "DNI no encontrado. Ingrese sus datos manualmente.";
            }
        }

        $this->currentStep = 2;
    }

    // ========================================================================
    // LÓGICA PASO 2: DATOS PERSONALES & UBIGEO
    // ========================================================================

    // Cuando cambia el Departamento
    public function updatedSelectedDep($value)
    {
        $this->provinces = Location::where('nombdep', $value)
            ->select('nombprov')->distinct()->orderBy('nombprov')->pluck('nombprov', 'nombprov');
        $this->selectedProv = '';
        $this->districts = [];
    }

    // Cuando cambia la Provincia
    public function updatedSelectedProv($value)
    {
        $this->districts = Location::where('nombdep', $this->selectedDep)
            ->where('nombprov', $value)
            ->orderBy('nombdist')
            ->pluck('nombdist', 'iddist'); // El valor es el IDDIST (Código Ubigeo)
        $this->selectedDistId = '';
    }

    public function submitStep2()
    {
        $this->validate([
            'name' => 'required',
            'paternal_surname' => 'required',
            'maternal_surname' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'birthday' => 'required|date',
            'selectedDistId' => 'required', // Ubigeo Nacimiento
            'photo' => 'nullable|image|max:2048', // 2MB Max
        ]);

        $this->currentStep = 3;
    }

    // ========================================================================
    // LÓGICA PASO 3: COLEGIO
    // ========================================================================

    // Buscador predictivo de colegios
    public function updatedSchoolSearch($value)
    {
        if (strlen($value) < 3) {
            $this->schoolResults = [];
            return;
        }
        $this->schoolResults = OriginSchool::where('name', 'like', '%' . $value . '%')
            ->take(5)
            ->get();
    }

    public function selectSchool($id, $name)
    {
        $this->selectedSchoolId = $id;
        $this->selectedSchoolName = $name;
        $this->schoolSearch = $name;
        $this->schoolResults = []; // Ocultar lista
    }

    public function submitStep3()
    {
        $this->validate([
            'selectedSchoolId' => 'required', // Debe seleccionar de la lista o implementar lógica de "Nuevo Colegio"
            'schoolYear' => 'required|digits:4',
        ], ['selectedSchoolId.required' => 'Debe seleccionar un colegio válido.']);

        $this->currentStep = 4;
    }

    // ========================================================================
    // LÓGICA PASO 4: REGISTRO FINAL
    // ========================================================================

    public function submitFinal()
    {
        $this->validate([
            'selectedOfferingId' => 'required',
            'selectedModalityId' => 'required',
            'selectedFinancialEntityId' => 'required',
            'paymentCode' => 'required',
        ]);

        DB::transaction(function () {
            // 1. Guardar/Actualizar Usuario
            $user = User::updateOrCreate(
                ['document_number' => $this->dni],
                [
                    'name' => $this->name,
                    // Concatenamos apellidos para guardar en 'lastname' como string único
                    'lastname' => $this->paternal_surname . ' ' . $this->maternal_surname,
                    'email' => $this->email,
                    // Si es nuevo, contraseña es DNI. Si existe, no la tocamos.
                    'password' => $this->is_new_user ? Hash::make($this->dni) : User::where('document_number', $this->dni)->first()->password,
                ]
            );

            // Asignar rol 'Estudiante' o 'Postulante' (Si usas Spatie)
            // $user->assignRole('Postulante'); 

            // 2. Guardar Foto
            $photoPath = null;
            if ($this->photo) {
                $photoPath = $this->photo->store('applicants', 'public');
            }

            // 3. Guardar Postulante
            Applicant::create([
                'user_id' => $user->id,
                'phone' => $this->phone,
                'address' => $this->address,
                'gender' => $this->gender,
                'birthday' => $this->birthday,
                'ubigeo_birth_id' => $this->selectedDistId,
                'photo_url' => $photoPath,

                'origin_school_id' => $this->selectedSchoolId,
                'school_graduation_year' => $this->schoolYear,

                'admission_offering_id' => $this->selectedOfferingId,
                'admission_modality_id' => $this->selectedModalityId,
                'financial_entity_id' => $this->selectedFinancialEntityId,
                'payment_operation_code' => $this->paymentCode,

                'application_status' => 'registered',
                'registration_step' => 5, // Proceso completado
                'code' => $this->dni, // Usamos el DNI como código
            ]);
        });

        session()->flash('message', '¡Inscripción exitosa!');
        // Aquí podrías redirigir a una página de "Éxito" que permita descargar el PDF
        // return redirect()->route('admission.success'); 
    }

    public function render()
    {
        return view('livewire.pages.admission.applicant-registration-wizard');
    }
}
