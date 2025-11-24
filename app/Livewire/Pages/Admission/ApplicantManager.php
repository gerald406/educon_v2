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
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ApplicantManager extends Component
{
    use WithPagination, WithFileUploads;

    // --- BÚSQUEDA PRINCIPAL ---
    public $search = '';

    // --- FORMULARIO: DATOS DE IDENTIFICACIÓN ---
    // [CORRECCIÓN] Usamos nombres consistentes con la vista y reglas
    public $searchDni = '';
    public $dni = '';
    public $name = '';
    public $paternal_surname = '';
    public $maternal_surname = '';
    public $email = '';
    public $is_new_user = true;

    // --- FORMULARIO: DATOS DEL POSTULANTE ---
    public $phone = '';
    public $address = '';
    public $gender = '';
    public $birthday = '';
    public $photo;
    public $photo_url_db;

    // Ubigeo
    public $ubigeoSearch = '';
    public $ubigeoResults = [];
    public $selectedDistId = null;
    public $selectedUbigeoName = '';

    // Colegio
    public $schoolSearch = '';
    public $schoolResults = [];
    public $selectedSchoolId = null;
    public $selectedSchoolName = '';
    public $schoolYear = '';

    // Datos Académicos y Pago
    public $selectedOfferingId = '';
    public $selectedModalityId = '';
    public $selectedFinancialEntityId = '';
    public $paymentCode = '';
    public $examScore = null;

    // --- ESTADO ---
    public ?User $editingUser = null;
    public ?Applicant $editingApplicant = null;
    public $isModalOpen = false;

    // Catálogos
    public Collection $offerings;
    public Collection $modalities;
    public Collection $financialEntities;

    protected function personService()
    {
        return new PersonDataService();
    }

    public function mount()
    {
        $this->offerings = new Collection();
        $this->modalities = new Collection();
        $this->financialEntities = new Collection();
        $this->loadCatalogs();
    }

    public function loadCatalogs()
    {
        $this->modalities = AdmissionModality::where('is_active', true)->get();
        $this->financialEntities = FinancialEntity::where('is_active', true)->get();
        $this->offerings = AdmissionOffering::with(['career', 'shift'])
            ->where('is_active', true)
            ->get();
    }

    // --- BÚSQUEDA DNI ---
    public function searchPersonByDni()
    {
        $this->validate(['searchDni' => 'required|digits:8']);

        $user = User::where('document_number', $this->searchDni)->first();

        if ($user) {
            $this->fillUserData($user);
            $this->is_new_user = false;
            $this->dispatch('swal', ['icon' => 'info', 'title' => 'Encontrado', 'text' => 'Usuario encontrado en el sistema.']);
        } else {
            $apiData = $this->personService()->search($this->searchDni);

            if ($apiData) {
                $this->is_new_user = true;
                $this->dni = $apiData['dni'];
                $this->name = $apiData['nombres'];
                $this->paternal_surname = $apiData['apellido_paterno'];
                $this->maternal_surname = $apiData['apellido_materno'];

                // Limpiar otros campos
                $this->email = '';
                $this->phone = '';
                $this->address = '';
                $this->gender = '';
                $this->birthday = '';
                $this->selectedDistId = null;
                $this->selectedUbigeoName = '';
                $this->ubigeoSearch = '';

                $this->dispatch('swal', ['icon' => 'success', 'title' => 'Encontrado', 'text' => 'Datos recuperados de RENIEC.']);
            } else {
                $this->is_new_user = true;
                $this->dni = $this->searchDni;
                $this->name = '';
                $this->paternal_surname = '';
                $this->maternal_surname = '';
                $this->dispatch('swal', ['icon' => 'warning', 'title' => 'No Encontrado', 'text' => 'DNI no encontrado. Ingrese datos manualmente.']);
            }
        }
    }

    public function fillUserData(User $user)
    {
        $this->editingUser = $user;
        $this->dni = $user->document_number;
        $this->name = $user->name;

        // Separar apellidos desde 'lastname'
        $parts = explode(' ', $user->lastname);
        $this->paternal_surname = $parts[0] ?? '';
        $this->maternal_surname = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : '';

        $this->email = $user->email;

        if ($user->applicant) {
            $this->editingApplicant = $user->applicant;
            $this->phone = $user->applicant->phone;
            $this->address = $user->applicant->address;
            $this->gender = $user->applicant->gender;
            $this->birthday = $user->applicant->birthday ? $user->applicant->birthday->format('Y-m-d') : '';
            $this->photo_url_db = $user->applicant->photo_url;

            if ($user->applicant->birthLocation) {
                $loc = $user->applicant->birthLocation;
                $this->selectedDistId = $loc->iddist;
                $this->selectedUbigeoName = $loc->full_name;
            }
            if ($user->applicant->originSchool) {
                $this->selectedSchoolId = $user->applicant->origin_school_id;
                $this->selectedSchoolName = $user->applicant->originSchool->name;
            }
            $this->schoolYear = $user->applicant->school_graduation_year;

            $this->selectedOfferingId = $user->applicant->admission_offering_id;
            $this->selectedModalityId = $user->applicant->admission_modality_id;
            $this->selectedFinancialEntityId = $user->applicant->financial_entity_id;
            $this->paymentCode = $user->applicant->payment_operation_code;
            $this->examScore = $user->applicant->exam_score;
        }
    }

    // --- BUSCADORES INTELIGENTES ---
    public function updatedUbigeoSearch($value)
    {
        if (strlen($value) < 2) {
            $this->ubigeoResults = [];
            return;
        }
        $this->ubigeoResults = Location::where('nombdist', 'like', "%$value%")
            ->orWhere('nombprov', 'like', "%$value%")
            ->take(10)
            ->get()
            ->toArray();
    }

    public function selectUbigeo($id, $name)
    {
        $this->selectedDistId = $id;
        $this->selectedUbigeoName = $name;
        $this->ubigeoSearch = '';
        $this->ubigeoResults = [];
    }

    public function updatedSchoolSearch($value)
    {
        if (strlen($value) < 2) {
            $this->schoolResults = [];
            return;
        }
        $this->schoolResults = OriginSchool::with('location')
            ->where('name', 'like', "%$value%")
            ->take(5)
            ->get();
    }

    public function selectSchool($id, $name)
    {
        $this->selectedSchoolId = $id;
        $this->selectedSchoolName = $name;
        $this->schoolSearch = '';
        $this->schoolResults = [];
    }

    // --- CRUD ---

    /**
     * [CORRECCIÓN CRÍTICA]
     * Las claves de este array deben coincidir EXACTAMENTE con los nombres de las propiedades públicas.
     */
    protected function rules()
    {
        return [
            'dni' => ['required', 'digits:8', Rule::unique('users', 'document_number')->ignore($this->editingUser?->id)],
            'name' => 'required|string|max:255',
            'paternal_surname' => 'required|string|max:255',
            'maternal_surname' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->editingUser?->id)],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'gender' => 'required|in:masculino,femenino',
            'birthday' => 'required|date',
            'photo' => 'nullable|image|max:2048',

            'selectedDistId' => 'required',
            'selectedSchoolId' => 'required',
            'schoolYear' => 'required|digits:4',
            'selectedOfferingId' => 'required',
            'selectedModalityId' => 'required',
            'selectedFinancialEntityId' => 'required',
            'paymentCode' => 'required',

            'examScore' => 'nullable|numeric|min:0|max:20',
        ];
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(Applicant $applicant)
    {
        $this->resetForm();
        $this->fillUserData($applicant->user);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->resetExcept('offerings', 'modalities', 'financialEntities');
        $this->schoolResults = [];
        $this->ubigeoResults = [];
        $this->photo = null;
        $this->resetErrorBag();
    }

    public function save()
    {
        $this->validate(); // Valida usando las reglas corregidas

        try {
            DB::transaction(function () {
                // 1. Usuario
                $user = User::updateOrCreate(
                    ['id' => $this->editingUser?->id],
                    [
                        'name' => $this->name, // [CORREGIDO]
                        'lastname' => $this->paternal_surname . ' ' . $this->maternal_surname, // [CORREGIDO]
                        'document_number' => $this->dni, // [CORREGIDO]
                        'email' => $this->email,
                        'password' => $this->editingUser ? $this->editingUser->password : Hash::make($this->dni),
                    ]
                );

                if (!$this->editingUser) {
                    $user->assignRole('Estudiante');
                }

                // 2. Foto
                $photoPath = $this->editingApplicant?->photo_url;
                if ($this->photo) {
                    if ($photoPath) Storage::disk('public')->delete($photoPath);
                    $photoPath = $this->photo->store('applicants', 'public');
                }

                // 3. Postulante
                Applicant::updateOrCreate(
                    ['id' => $this->editingApplicant?->id],
                    [
                        'user_id' => $user->id,
                        'phone' => $this->phone,
                        'address' => $this->address,
                        'gender' => $this->gender,
                        'birthday' => $this->birthday ?: null,
                        'ubigeo_birth_id' => $this->selectedDistId,
                        'photo_url' => $photoPath,

                        'origin_school_id' => $this->selectedSchoolId,
                        'school_graduation_year' => $this->schoolYear,

                        'admission_offering_id' => $this->selectedOfferingId,
                        'admission_modality_id' => $this->selectedModalityId,
                        'financial_entity_id' => $this->selectedFinancialEntityId,
                        'payment_operation_code' => $this->paymentCode,

                        'code' => $this->dni,
                        'exam_score' => $this->examScore,
                        // 'application_status' => $this->editingApplicant ? $this->editingApplicant->application_status : 'registered',
                        'application_status' => $this->editingApplicant ? $this->editingApplicant->application_status : 'registrado',
                        'registration_step' => 5,
                    ]
                );
            });

            $this->closeModal();
            $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Guardado!', 'text' => 'Postulante registrado correctamente.']);
        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo guardar: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $query = Applicant::with(['user', 'admissionOffering.career', 'admissionModality']);

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%')
                    ->orWhere('document_number', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.pages.admission.applicant-manager', [
            'applicants' => $query->paginate(10)
        ]);
    }
}
