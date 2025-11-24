<?php

use App\Http\Controllers\AdmissionDocumentController;
use Illuminate\Support\Facades\Route;

// Importaciones de todos los componentes
use App\Livewire\Pages\Settings\Classrooms\ClassroomManager;
use App\Livewire\Pages\Settings\PaymentConcepts\PaymentConceptManager;
use App\Livewire\Pages\Settings\AcademicYears\AcademicYearManager;
use App\Livewire\Pages\Settings\Shifts\ShiftManager;
use App\Livewire\Pages\Settings\EvaluationTypes\EvaluationTypeManager;
use App\Livewire\Pages\Settings\SystemSettings\SystemSettingsManager;
use App\Livewire\Pages\Settings\Institution\InstitutionManager;

use App\Livewire\Pages\Academic\Careers\CareerManager;
use App\Livewire\Pages\Academic\StudyPlans\StudyPlanManager;
use App\Livewire\Pages\Academic\Modules\ModuleManager;
use App\Livewire\Pages\Academic\DidacticUnits\DidacticUnitManager;
use App\Livewire\Pages\Academic\Prerequisites\PrerequisiteManager;

use App\Livewire\Pages\People\Teachers\TeacherManager;
use App\Livewire\Pages\People\Students\StudentManager;

use App\Livewire\Pages\AcademicProcess\AcademicPeriods\AcademicPeriodManager;
use App\Livewire\Pages\AcademicProcess\TeacherAssignments\TeacherAssignmentManager;
use App\Livewire\Pages\AcademicProcess\Schedules\ScheduleManager;
use App\Livewire\Pages\AcademicProcess\SyllabusApproval;

use App\Livewire\Pages\Evaluation\Grades\GradeManager;
use App\Livewire\Pages\Evaluation\Attendances\AttendanceManager;
use App\Livewire\Pages\Teacher\MySyllabi;
use App\Livewire\Pages\Teacher\ActivityManager;
use App\Livewire\Pages\Teacher\SubmissionReview;
use App\Livewire\Pages\Teacher\AttendanceReport;
use App\Livewire\Pages\Teacher\CumulativeAttendanceReport;

use App\Livewire\Pages\Enrollment\EnrollmentProcess;

use App\Livewire\Pages\Treasury\PaymentManager;
use App\Livewire\Pages\Treasury\CashSessionManager;
use App\Livewire\Pages\Treasury\VoucherSeriesManager;
use App\Livewire\Pages\Treasury\TupaPointOfSale;
use App\Livewire\Pages\Treasury\CreditNoteManager;

use App\Livewire\Pages\Certification\CertificateManager;
use App\Livewire\Pages\Certification\InternshipManager;
use App\Livewire\Pages\Certification\GraduationProcessManager;
use App\Livewire\Pages\Certification\MeritRankingManager;

use App\Livewire\Pages\Services\Library\LibraryResourceManager;
use App\Livewire\Pages\Services\Library\LibraryLoanManager;
use App\Livewire\Pages\Services\Tutoring\TutoringManager;

use App\Livewire\Pages\Reports\ReportManager;

use App\Livewire\Pages\Admission\ApplicantManager;
use App\Livewire\Pages\Admission\AdmissionModalityManager;
use App\Livewire\Pages\Admission\AdmissionOfferingManager;
use App\Livewire\Pages\Admission\FinancialEntityManager;

use App\Livewire\Pages\Student\MyActivities;
use App\Livewire\Pages\Student\MyAttendances;

use App\Livewire\Pages\Communication\AnnouncementManager;

use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CashSessionController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí es donde puedes registrar las rutas web para tu aplicación. Estas
| rutas son cargadas por el RouteServiceProvider y todas ellas
| serán asignadas al grupo de middleware "web".
|
*/

// Ruta de bienvenida (Pública)
Route::get('/', function () {
    return view('welcome');
});

// Rutas de Jetstream (Dashboard y Profile)
// Estas rutas están disponibles para CUALQUIER usuario autenticado.
// El componente del Dashboard se encargará de mostrar el contenido correcto.
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Añadimos la ruta de perfil que faltaba en tu archivo
    Route::get('/user/profile', function () {
        return view('profile.show');
    })->name('profile.show');
});


// --- [INICIO DE RUTAS PROTEGIDAS POR PERMISOS] ---

// Grupo de Rutas para Configuración
// [MODIFICADO] Añadido middleware de permiso
Route::prefix('settings')->middleware(['auth', 'verified', 'permission:gestionar-configuracion'])->name('settings.')->group(function () {
    Route::get('classrooms', ClassroomManager::class)->name('classrooms');
    Route::get('payment-concepts', PaymentConceptManager::class)->name('payment-concepts');
    Route::get('academic-years', AcademicYearManager::class)->name('academic-years');
    Route::get('shifts', ShiftManager::class)->name('shifts');
    Route::get('evaluation-types', EvaluationTypeManager::class)->name('evaluation-types');
    Route::get('system-settings', SystemSettingsManager::class)->name('system-settings');
    Route::get('institution', InstitutionManager::class)->name('institution');
});

// Grupo de Rutas para Gestión Académica
// [MODIFICADO] Añadido middleware de permiso
Route::prefix('academic')->middleware(['auth', 'verified', 'permission:gestionar-estructura-academica'])->name('academic.')->group(function () {
    Route::get('careers', CareerManager::class)->name('careers');
    Route::get('study-plans', StudyPlanManager::class)->name('study-plans');
    Route::get('modules', ModuleManager::class)->name('modules');
    Route::get('didactic-units', DidacticUnitManager::class)->name('didactic-units');
    Route::get('prerequisites', PrerequisiteManager::class)->name('prerequisites');
});

// Grupo de Rutas para Gestión de Personas
// [MODIFICADO] Añadidos middlewares de permisos específicos a cada ruta
Route::prefix('people')->middleware(['auth', 'verified'])->name('people.')->group(function () {
    Route::get('teachers', TeacherManager::class)->middleware('permission:gestionar-docentes')->name('teachers');
    Route::get('students', StudentManager::class)->middleware('permission:gestionar-estudiantes')->name('students');
});

// Grupo de Rutas para Procesos Académicos
// [MODIFICADO] Añadidos middlewares de permisos específicos a cada ruta
Route::prefix('academic-process')->middleware(['auth', 'verified'])->name('academic-process.')->group(function () {
    Route::get('academic-periods', AcademicPeriodManager::class)->middleware('permission:gestionar-periodos')->name('academic-periods');
    Route::get('teacher-assignments', TeacherAssignmentManager::class)->middleware('permission:gestionar-carga-academica')->name('teacher-assignments');
    Route::get('schedules', ScheduleManager::class)->middleware('permission:gestionar-horarios')->name('schedules');
    Route::get('syllabus-approval', SyllabusApproval::class)->middleware('permission:aprobar-silabos')->name('syllabus-approval');
});

// Grupo de Rutas para Evaluación (Docentes y roles superiores)
// [MODIFICADO] Añadido middleware de rol
Route::prefix('evaluation')->middleware(['auth', 'verified', 'role:Docente|Coordinador|Administrador'])->name('evaluation.')->group(function () {
    Route::get('grades', GradeManager::class)->name('grades');
    Route::get('attendances', AttendanceManager::class)->name('attendances');
});

// Grupo de Rutas para Tesorería
// [MODIFICADO] Añadido middleware de permiso
Route::prefix('treasury')->middleware(['auth', 'verified', 'permission:registrar-pagos'])->name('treasury.')->group(function () {
    Route::get('payments', PaymentManager::class)->name('payments');
    Route::get('cash-sessions', CashSessionManager::class)->middleware('permission:gestionar-sesiones-caja')->name('cash-sessions');
    Route::get('voucher-series', VoucherSeriesManager::class)->middleware('permission:gestionar-correlativos')->name('voucher-series');
    Route::get('tupa-pos', TupaPointOfSale::class)->middleware('permission:registrar-tramites')->name('tupa-pos');
    Route::get('voucher/{voucher}/download', [VoucherController::class, 'download'])->name('voucher.download');
    Route::get('credit-notes', CreditNoteManager::class)->middleware('permission:anular-comprobantes')->name('credit-notes');
    Route::get('credit-note/{creditNote}/download', [VoucherController::class, 'downloadCreditNote'])->name('credit-note.download');
    Route::get('cash-session/{session}/report/{type}', [CashSessionController::class, 'download'])
        ->where('type', 'x|z') // Solo permite 'x' o 'z'
        ->name('cash-session.report');
});

// Grupo de Rutas para Matrícula (Estudiantes y Admin)
// [MODIFICADO] Añadido middleware de rol
Route::prefix('enrollment')->middleware(['auth', 'verified', 'role:Estudiante|Administrador'])->name('enrollment.')->group(function () {
    Route::get('process', EnrollmentProcess::class)->name('process');
});

// Grupo de Rutas para Egreso y Certificación
// [MODIFICADO] Añadido middleware de permiso
Route::prefix('certification')->middleware(['auth', 'verified', 'permission:gestionar-certificacion'])->name('certification.')->group(function () {
    Route::get('certificates', CertificateManager::class)->name('certificates');
    Route::get('internships', InternshipManager::class)->name('internships');
    Route::get('graduation-processes', GraduationProcessManager::class)->name('graduation-processes');
    Route::get('merit-rankings', MeritRankingManager::class)->middleware('permission:gestionar-cuadro-meritos')->name('merit-rankings');
});

// Grupo de Rutas para Docentes (Sílabos)
// [MODIFICADO] Añadido middleware de rol
Route::prefix('teacher')->middleware(['auth', 'verified', 'role:Docente|Coordinador|Administrador'])->name('teacher.')->group(function () {
    Route::get('my-syllabi', MySyllabi::class)->name('my-syllabi');
    Route::get('activities', ActivityManager::class)->middleware('permission:gestionar-actividades')->name('activities');
    Route::get('submissions', SubmissionReview::class)->middleware('permission:revisar-entregas')->name('submissions');
    Route::get('attendance-report', AttendanceReport::class)->middleware('permission:ver-reporte-asistencia')->name('attendance-report');
    Route::get('cumulative-attendance-report', CumulativeAttendanceReport::class)->middleware('permission:ver-reporte-acumulativo-asistencia')->name('cumulative-attendance-report');
});

// Grupo de Rutas para Servicios
// [MODIFICADO] Añadido middleware de permiso
Route::prefix('services')->middleware(['auth', 'verified', 'permission:gestionar-biblioteca'])->name('services.')->group(function () {
    Route::get('library-resources', LibraryResourceManager::class)->name('library-resources');
    Route::get('library-loans', LibraryLoanManager::class)->name('library-loans');
    Route::get('tutorings', TutoringManager::class)->name('tutorings');
});

// [NUEVO GRUPO] Grupo de Rutas para Reportes (Solo Admin)
Route::prefix('reports')->middleware(['auth', 'verified', 'role:Administrador'])->name('reports.')->group(function () {
    Route::get('/', ReportManager::class)->name('index');
});

// [NUEVO GRUPO] Grupo de Rutas para Admisión
Route::prefix('admission')->middleware(['auth', 'verified', 'permission:gestionar-admision'])->name('admission.')->group(function () {
    Route::get('applicants', ApplicantManager::class)->name('applicants');
    Route::get('modalities', AdmissionModalityManager::class)->name('modalities');
    Route::get('financial-entities', FinancialEntityManager::class)->name('financial-entities');
    Route::get('offerings', AdmissionOfferingManager::class)->name('offerings');
    Route::get('constancia/{applicant}', [AdmissionDocumentController::class, 'constancia'])->name('constancia');
    Route::get('ficha/{applicant}', [AdmissionDocumentController::class, 'ficha'])->name('ficha');
});


// [NUEVO GRUPO] Grupo de Rutas para Estudiantes
Route::prefix('student')->middleware(['auth', 'verified', 'role:Estudiante|Administrador'])->name('student.')->group(function () {
    Route::get('my-activities', MyActivities::class)->middleware('permission:entregar-actividades')->name('my-activities');
    Route::get('my-attendances', MyAttendances::class)->middleware('permission:ver-mis-asistencias')->name('my-attendances');
});

// [NUEVO GRUPO] Grupo de Rutas para Comunicación
Route::prefix('communication')->middleware(['auth', 'verified', 'permission:gestionar-anuncios'])->name('communication.')->group(function () {
    Route::get('announcements', AnnouncementManager::class)->name('announcements');
});


Route::get('voucher/{voucher}/download', [VoucherController::class, 'download'])->name('voucher.download');