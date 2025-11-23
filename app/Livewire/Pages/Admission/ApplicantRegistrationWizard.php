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

#[Layout('layouts.guest')] // Usamos el layout guest para postulantes externos
class ApplicantRegistrationWizard extends Component
{
    use WithFileUploads;

    // --- CONTROL DE PASOS ---
    public $currentStep = 1;

    // --- PASO 1: IDENTIFICACIÓN ---
    public $dni = '';
    public $message = '';

    // --- PASO 2: DATOS PERSONALES ---
    public $name = '';
    public $lastname = ''; // Apellido Paterno + Materno (según tu migración modificada)
    public $email = '';
    public $phone = '';
    public $address = '';
    public $gender = '';
    public $birthday = '';
    public $photo;
    public $is_new_user = true;

    // Ubigeo Nacimiento
    public $departments = [], $provinces = [], $districts = [];
    public $selectedDep = '', $selectedProv = '', $selectedDist = '';

    // --- PASO 3: COLEGIO ---
    public $schoolSearch = '';
    public $schoolResults = [];
    public $selectedSchoolId = null;
    public $selectedSchoolName = '';
    public $schoolYear = '';

    // --- PASO 4: POSTULACIÓN ---
    public $selectedOfferingId = '';
    public $selectedModalityId = '';
    public $selectedFinancialEntityId = '';
    public $paymentCode = '';

    // Listas de carga
    public $offerings = [];
    public $modalities = [];
    public $financialEntities = [];

    // Inyección de Dependencia del Servicio
    protected function personService()
    {
        return new PersonDataService();
    }

    public function mount()
    {
        // Cargar departamentos iniciales
        $this->departments = Location::select('nombdep')->distinct()->orderBy('nombdep')->pluck('nombdep', 'nombdep');
        $this->loadCatalogs();
    }

    public function loadCatalogs()
    {
        // Cargar Modalidades y Bancos para el Paso 4
        $this->modalities = AdmissionModality::where('is_active', true)->get();
        $this->financialEntities = FinancialEntity::where('is_active', true)->get();

        // Cargar Oferta (Carrera + Turno) del periodo activo (Simplificado)
        // Aquí deberías filtrar por el periodo de admisión activo
        $this->offerings = AdmissionOffering::with(['career', 'shift'])
            ->where('is_active', true)
            ->get();
    }

    // --- LÓGICA PASO 1: BUSCAR DNI ---
    public function searchDni()
    {
        $this->validate(['dni' => 'required|digits:8']);
        $this->resetErrorBag();
        $this->message = '';

        // 1. Buscar en BD Local (Users)
        $user = User::where('document_number', $this->dni)->first();

        if ($user) {
            $this->is_new_user = false;
            $this->name = $user->name;
            $this->lastname = $user->lastname; // Usando tu campo nuevo
            $this->email = $user->email;
            $this->message = "Usuario encontrado en el sistema. Verifique sus datos.";

            // Si ya tiene postulación, podríamos redirigir o cargar
            if ($user->applicant) {
                // Lógica para retomar inscripción... (Omitida por brevedad)
            }
        } else {
            // 2. Buscar en API Externa
            $this->is_new_user = true;
            $apiData = $this->personService()->search($this->dni);

            if ($apiData) {
                $this->name = $apiData['nombres'];
                // Concatenamos apellidos para tu campo 'lastname'
                $this->lastname = $apiData['apellido_paterno'] . ' ' . $apiData['apellido_materno'];
                $this->message = "Datos encontrados en RENIEC.";
            } else {
                $this->message = "DNI no encontrado. Por favor ingrese sus datos manualmente.";
                $this->name = '';
                $this->lastname = '';
            }
        }

        $this->currentStep = 2;
    }

    // --- LÓGICA UBIGEO (Cascada) ---
    public function updatedSelectedDep($value)
    {
        $this->provinces = Location::where('nombdep', $value)
            ->select('nombprov')
            ->distinct()
            ->orderBy('nombprov')
            ->pluck('nombprov', 'nombprov');
        $this->selectedProv = '';
        $this->districts = [];
    }

    public function updatedSelectedProv($value)
    {
        $this->districts = Location::where('nombdep', $this->selectedDep)
            ->where('nombprov', $value)
            ->select('iddist', 'nombdist')
            ->orderBy('nombdist')
            ->pluck('nombdist', 'iddist'); // El value será el iddist (código)
        $this->selectedDist = '';
    }

    // --- LÓGICA PASO 2 -> 3 ---
    public function submitStep2()
    {
        $this->validate([
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'gender' => 'required',
            'birthday' => 'required|date',
            'selectedDist' => 'required', // Ubigeo
            'photo' => 'nullable|image|max:2048', // Opcional si ya tiene
        ]);

        $this->currentStep = 3;
    }

    // --- LÓGICA PASO 3: COLEGIO ---
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
        $this->schoolSearch = $name; // Mostrar nombre en input
        $this->schoolResults = [];
    }

    public function submitStep3()
    {
        $this->validate([
            'selectedSchoolId' => 'required',
            'schoolYear' => 'required|digits:4',
        ], ['selectedSchoolId.required' => 'Debe seleccionar un colegio de la lista.']);

        $this->currentStep = 4;
    }

    // --- LÓGICA FINAL (GUARDAR TODO) ---
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
                    'lastname' => $this->lastname,
                    'email' => $this->email,
                    'password' => $this->is_new_user ? Hash::make($this->dni) : User::where('document_number', $this->dni)->first()->password,
                    // Asegurar rol 'Estudiante' o 'Postulante' si usas Spatie
                ]
            );

            // 2. Subir foto si existe
            $photoPath = null;
            if ($this->photo) {
                $photoPath = $this->photo->store('applicants', 'public');
            }

            // 3. Crear Postulante (Applicant)
            $applicant = Applicant::create([
                'user_id' => $user->id,

                // Datos Paso 2
                'phone' => $this->phone,
                'address' => $this->address,
                'gender' => $this->gender,
                'birthday' => $this->birthday,
                'ubigeo_birth_id' => $this->selectedDist,
                'photo_url' => $photoPath,

                // Datos Paso 3 (Colegio)
                'origin_school_id' => $this->selectedSchoolId,
                'school_graduation_year' => $this->schoolYear,

                // Datos Paso 4 (Académico y Pago)
                'admission_offering_id' => $this->selectedOfferingId,
                'admission_modality_id' => $this->selectedModalityId,
                'financial_entity_id' => $this->selectedFinancialEntityId,
                'payment_operation_code' => $this->paymentCode,

                'application_status' => 'registrado',
                'registration_step' => 5, // Finalizado
                'code' => $this->dni, // Código temporal
            ]);
        });

        session()->flash('message', '¡Inscripción realizada con éxito!');
        // Redirigir a página de éxito/descarga
        // return redirect()->route('admission.success', ['id' => $applicant->id]);
    }

    public function render()
    {
        return view('livewire.pages.admission.applicant-registration-wizard');
    }
}
