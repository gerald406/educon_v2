<?php

namespace App\Livewire\Pages\Admission;

use App\Models\AdmissionModality;
use App\Models\AdmissionOffering;
use App\Models\Applicant;
use App\Models\DidacticUnit; // [NUEVO]
use App\Models\Enrollment;   // [NUEVO]
use App\Models\FinancialEntity;
use App\Models\Location;
use App\Models\OriginSchool;
use App\Models\User;
use App\Models\Student; // Importar arriba
use App\Models\AcademicPeriod; // Importar
use App\Models\PaymentConcept; // Importar
use App\Models\Registration; // [NUEVO]
use App\Models\StudentPayment; // Importar
use App\Models\StudyPlan;
use App\Models\TeacherAssignment; // [NUEVO]
use App\Models\Voucher;      // [NUEVO]
// use App\livewire\Pages\Admission\On;
use Livewire\Attributes\On;

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

    // --- [NUEVAS PROPIEDADES PARA MIGRACIÓN] ---
    public $isMigrationModalOpen = false;
    public ?Applicant $migratingApplicant = null;
    public $migrationStudyPlans = [];
    public $selectedMigrationStudyPlanId = '';
    public $migrationStudentCode = ''; // Para previsualizar o editar el código

    // [NUEVO] Campo para el Voucher
    public $migrationVoucherNumber = '';

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

    /**
     * Abre el modal de confirmación para convertir al postulante.
     */
    public function openMigrationModal($applicantId)
    {
        $this->migratingApplicant = Applicant::with(['user', 'admissionOffering.career'])->find($applicantId);

        if (!$this->migratingApplicant) return;

        // 1. Verificar si ya es estudiante
        if (Student::where('user_id', $this->migratingApplicant->user_id)->exists()) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Ya registrado', 'text' => 'Este postulante ya tiene un código de estudiante.']);
            return;
        }

        // 2. Cargar planes de estudio
        $careerId = $this->migratingApplicant->admissionOffering->career_id;
        $this->migrationStudyPlans = StudyPlan::where('career_id', $careerId)
            ->where('status', 'active')
            ->orderBy('start_date', 'desc')
            ->get();

        $this->selectedMigrationStudyPlanId = $this->migrationStudyPlans->first()?->id;

        // 3. [CORREGIDO] Generar código de estudiante (Lógica robusta)
        $year = date('Y');

        // Buscamos el último código que empiece con 'E' + Año (ej. E2025)
        // Usamos RAW length para asegurar que cortamos bien
        $lastStudent = Student::where('code', 'like', "E{$year}%")
            ->select('code')
            ->orderByRaw('LENGTH(code) DESC') // Primero por longitud para evitar problemas de orden
            ->orderBy('code', 'desc')       // Luego por valor
            ->first();

        $sequence = 1;
        if ($lastStudent) {
            // Extraemos los últimos dígitos (asumiendo formato E2025-XXXXX o E2025XXXX)
            // Quitamos 'E2025' (5 caracteres) o 'E2025-' (6 caracteres)
            // Ajusta esto según tu formato exacto. Asumiremos E2025-00001 (con guión)
            $numberPart = substr($lastStudent->code, 6);
            if (is_numeric($numberPart)) {
                $sequence = intval($numberPart) + 1;
            }
        }

        $this->migrationStudentCode = "E{$year}-" . str_pad($sequence, 5, '0', STR_PAD_LEFT);
        $this->migrationVoucherNumber = ''; // Resetear voucher

        $this->isMigrationModalOpen = true;
    }

    /**
     * Ejecuta la conversión final.
     */
    public function processMigration()
    {
        $this->validate([
            'selectedMigrationStudyPlanId' => 'required|exists:study_plans,id',
            'migrationStudentCode' => 'required|unique:students,code',
            'migrationVoucherNumber' => 'required|string', // Validar que ingresó algo
        ]);

        // 1. Validar Voucher
        // Buscamos un voucher emitido a este usuario que no esté anulado

        // 1. Validar Voucher
        $voucher = Voucher::where('number', $this->migrationVoucherNumber)
            ->where('client_id', $this->migratingApplicant->user_id)
            ->where('status', 'issued')
            ->first();

        if (!$voucher) {
            $this->dispatch('swal', [
                'icon' => 'error',
                'title' => 'Voucher no válido',
                'text' => 'No se encontró un comprobante válido con ese número para este postulante.'
            ]);
            return;
        }

        DB::transaction(function () use ($voucher) {
            $applicant = $this->migratingApplicant;
            $offering = $applicant->admissionOffering;

            // Crear Estudiante
            $student = Student::create([
                'user_id' => $applicant->user_id,
                'career_id' => $offering->career_id,
                'study_plan_id' => $this->selectedMigrationStudyPlanId,
                'code' => $this->migrationStudentCode,
                'current_semester' => 1,
                'academic_status' => 'regular',
                'admission_date' => now(),
                'applicant_id' => $applicant->id,
                'phone' => $applicant->phone,
                'address' => $applicant->address,
                'gender' => $applicant->gender,
                'birthday' => $applicant->birthday,
                'ubigeo_birth_id' => $applicant->ubigeo_birth_id,
                'origin_school_id' => $applicant->origin_school_id,
                'school_graduation_year' => $applicant->school_graduation_year,
                'photo_url' => $applicant->photo_url,
            ]);

            // Actualizar Rol y Estado
            $applicant->user->assignRole('Estudiante');
            $applicant->update(['application_status' => 'aprobado']);

            // Crear Matrícula
            $period = AcademicPeriod::where('status', 'active')->first();

            if ($period) {
                $enrollment = Enrollment::create([
                    'student_id' => $student->id,
                    'academic_period_id' => $period->id,
                    'semester_enrolled' => 1,
                    'enrollment_type' => 'first_time',
                    'payment_status' => 'paid',
                    'status' => 'active',
                    'notes' => "Matrícula automática (Ingresante). Voucher: {$voucher->series}-{$voucher->number}",
                    'amount_paid' => $voucher->total_amount
                ]);

                // [FIX] Obtener IDs de Módulos primero, luego buscar Unidades Didácticas
                $moduleIds = DB::table('modules')
                    ->where('study_plan_id', $this->selectedMigrationStudyPlanId)
                    ->pluck('id');

                // Ahora buscamos las Unidades Didácticas del 1er semestre
                $units1stSemester = DidacticUnit::whereIn('module_id', $moduleIds)
                    ->where('semester', 1)
                    ->pluck('id');

                // Buscar Asignaciones Docentes (Secciones abiertas)
                $assignments = TeacherAssignment::whereIn('didactic_unit_id', $units1stSemester)
                    ->where('academic_period_id', $period->id)
                    ->where('shift_id', $offering->shift_id)
                    ->where('status', 'active')
                    ->get()
                    ->unique('didactic_unit_id'); // Una sección por curso

                foreach ($assignments as $assignment) {
                    Registration::create([
                        'enrollment_id' => $enrollment->id,
                        'teacher_assignment_id' => $assignment->id,
                        'status' => 'enrolled',
                        'registration_type' => 'mandatory'
                    ]);

                    $assignment->increment('current_enrolled');
                }
            }
        });

        $this->closeMigrationModal();
        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Matrícula Exitosa!', 'text' => "El ingresante {$this->migrationStudentCode} ha sido matriculado."]);
    }

    public function closeMigrationModal()
    {
        $this->isMigrationModalOpen = false;
        $this->migratingApplicant = null;
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

    public function confirmMigrateToStudent($applicantId)
    {
        $this->dispatch('swal:confirm', [
            'id' => $applicantId,
            'title' => '¿Registrar Ingresante?',
            'text' => 'Se creará un registro de estudiante y se generará la deuda de matrícula. El postulante debe haber pagado su derecho de admisión.',
            'onConfirmed' => 'migrateToStudent'
        ]);
    }

    #[On('migrateToStudent')]
    public function migrateToStudent($id)
    {
        $applicant = Applicant::with('user')->find($id);

        if (!$applicant) return;

        // 1. Verificar si ya es estudiante
        $exists = Student::where('user_id', $applicant->user_id)->exists();
        if ($exists) {
            $this->dispatch('swal', ['icon' => 'warning', 'title' => 'Ya registrado', 'text' => 'Este postulante ya tiene un código de estudiante.']);
            return;
        }

        DB::transaction(function () use ($applicant) {
            // 2. Generar Código de Estudiante (Lógica simple: Año + Correlativo)
            $year = date('Y');
            $lastStudent = Student::where('code', 'like', "$year%")->orderBy('code', 'desc')->first();
            $sequence = $lastStudent ? intval(substr($lastStudent->code, 4)) + 1 : 1;
            $studentCode = $year . str_pad($sequence, 4, '0', STR_PAD_LEFT);

            // 3. Crear Estudiante (Copiando datos)
            $student = Student::create([
                'user_id' => $applicant->user_id,
                'career_id' => $applicant->admissionOffering->career_id, // Carrera de la oferta
                'study_plan_id' => $this->selectedMigrationStudyPlanId,
                'code' => $studentCode,
                'current_semester' => 1,
                'academic_status' => 'regular',
                'admission_date' => now(),
                'admission_code' => $applicant->code,

                // Copia de datos
                'phone' => $applicant->phone,
                'address' => $applicant->address,
                'gender' => $applicant->gender,
                'birthday' => $applicant->birthday,
                'ubigeo_birth_id' => $applicant->ubigeo_birth_id,
                'origin_school_id' => $applicant->origin_school_id,
                'school_graduation_year' => $applicant->school_graduation_year,
                'photo_url' => $applicant->photo_url, // Copiamos la ruta
            ]);

            // 4. Actualizar Rol de Usuario
            $applicant->user->assignRole('Estudiante');
            $applicant->update(['application_status' => 'approved']); // O 'ingresante'

            // 5. Generar Deuda de Matrícula (Para que vaya a Caja)
            $period = AcademicPeriod::where('status', 'active')->first();
            $concept = PaymentConcept::where('code', 'MAT-REG')->first(); // Asegúrate que exista este concepto

            if ($period && $concept) {
                StudentPayment::create([
                    'student_id' => $student->id,
                    'payment_concept_id' => $concept->id,
                    'academic_period_id' => $period->id,
                    'original_amount' => $concept->amount,
                    'final_amount' => $concept->amount,
                    'due_date' => now()->addDays(5),
                    'status' => 'pending',
                    'registered_by_user_id' => auth()->id(),
                ]);
            }
        });

        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Bienvenido!', 'text' => 'El postulante ahora es un estudiante. Se ha generado su deuda de matrícula.']);
    }

    // [NUEVO] Confirmación de Eliminación
    public function confirmDelete($id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Postulante?',
            'text' => 'Esta acción enviará al postulante a la papelera. No se borrará el usuario del sistema.',
            'onConfirmed' => 'deleteApplicant'
        ]);
    }

    // [NUEVO] Ejecutar Eliminación
    #[On('deleteApplicant')]
    public function deleteApplicant($id)
    {
        $applicant = Applicant::find($id);

        if ($applicant) {
            // [CORRECCIÓN]
            // Antes tenías algo como: Student::where('applicant_id', $applicant->id)...
            // CAMBIA A: Buscar por 'user_id', que es el enlace real entre Postulante y Estudiante.
            $isStudent = \App\Models\Student::where('user_id', $applicant->user_id)->exists();

            // Validamos si ya ingresó o si ya existe como estudiante
            if ($applicant->application_status === 'approved' || $isStudent) {
                $this->dispatch('swal', [
                    'icon' => 'error',
                    'title' => 'No permitido',
                    'text' => 'No se puede eliminar un postulante que ya es estudiante o ha ingresado.'
                ]);
                return;
            }

            // Si pasa la validación, eliminamos (Soft Delete)
            $applicant->delete();

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => 'Eliminado',
                'text' => 'Postulante eliminado correctamente.'
            ]);
        }
    }

    public function render()
    {
        $query = Applicant::with(['user', 'admissionOffering.career', 'admissionModality'])
            ->has('user'); // Evita el error 500 de usuarios nulos

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('lastname', 'like', '%' . $this->search . '%')
                    ->orWhere('document_number', 'like', '%' . $this->search . '%');
            });
        }

        // Ordenar por fecha de creación descendente (los nuevos primero)
        $query->orderBy('created_at', 'desc');

        return view('livewire.pages.admission.applicant-manager', [
            'applicants' => $query->paginate(10)
        ]);
    }
}
