<?php

use Illuminate\Support\Facades\Route;
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

use App\Livewire\Pages\Evaluation\Grades\GradeManager;
use App\Livewire\Pages\Evaluation\Attendances\AttendanceManager;

use App\Livewire\Pages\Treasury\PaymentManager;

use App\Livewire\Pages\Enrollment\EnrollmentProcess;

use App\Livewire\Pages\Certification\CertificateManager;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});


// Grupo de Rutas para Configuración
Route::prefix('settings')->middleware(['auth', 'verified'])->name('settings.')->group(function () {
    // Rutas para la Gestión de Aulas y labs
    Route::get('classrooms', ClassroomManager::class)->name('classrooms');
    //Rutas para la Gestión de Conceptos de Pago
    Route::get('payment-concepts', PaymentConceptManager::class)->name('payment-concepts');
    //Rutas para la Gestión de Años Académicos
    Route::get('academic-years', AcademicYearManager::class)->name('academic-years');
    //Rutas para la Gestión de Turnos
    Route::get('shifts', ShiftManager::class)->name('shifts');
    //Rutas para la Gestión de Tipos de Evaluación
    Route::get('evaluation-types', EvaluationTypeManager::class)->name('evaluation-types');
    //Rutas para la Gestión de Configuraciones del Sistema
    Route::get('system-settings', SystemSettingsManager::class)->name('system-settings');
    //Rutas para la Gestión de la Institución
    Route::get('institution', InstitutionManager::class)->name('institution');
});

// [NUEVO GRUPO] Grupo de Rutas para Gestión Académica
Route::prefix('academic')->middleware(['auth', 'verified'])->name('academic.')->group(function () {
    //rutas para el Manejo de Carreras
    Route::get('careers', CareerManager::class)->name('careers');
    //Rutas para el Manejo de Planes de Estudio
    Route::get('study-plans', StudyPlanManager::class)->name('study-plans');
    //Rutas para el Manejo de Módulos
    Route::get('modules', ModuleManager::class)->name('modules');
    //Rutas para el Manejo de Unidades Didácticas
    Route::get('didactic-units', DidacticUnitManager::class)->name('didactic-units');
    //Rutas para el Manejo de Prerrequisitos
    Route::get('prerequisites', PrerequisiteManager::class)->name('prerequisites');
});


// [NUEVO GRUPO] Grupo de Rutas para Gestión de Personas
Route::prefix('people')->middleware(['auth', 'verified'])->name('people.')->group(function () {
    Route::get('teachers', TeacherManager::class)->name('teachers');
    Route::get('students', StudentManager::class)->name('students');
});


// [NUEVO GRUPO] Grupo de Rutas para Procesos Académicos
Route::prefix('academic-process')->middleware(['auth', 'verified'])->name('academic-process.')->group(function () {
    //Rutas para la Gestión de Períodos Académicos
    Route::get('academic-periods', AcademicPeriodManager::class)->name('academic-periods');
    //Rutas para la Gestión de Asignaciones de Docentes
    Route::get('teacher-assignments', TeacherAssignmentManager::class)->name('teacher-assignments');
    //Rutas para la Gestión de Horarios
    Route::get('schedules', ScheduleManager::class)->name('schedules');
});


// [NUEVO GRUPO] Grupo de Rutas para Evaluación
Route::prefix('evaluation')->middleware(['auth', 'verified'])->name('evaluation.')->group(function () {
    //Rutas para la Gestión de Calificaciones
    Route::get('grades', GradeManager::class)->name('grades');
    //Rutas para la Gestión de Asistencias
    Route::get('attendances', AttendanceManager::class)->name('attendances');
});

// [NUEVO GRUPO] Grupo de Rutas para Tesorería
Route::prefix('treasury')->middleware(['auth', 'verified'])->name('treasury.')->group(function () {
    Route::get('payments', PaymentManager::class)->name('payments');
});

// [NUEVO GRUPO] Grupo de Rutas para Matrícula (Estudiantes)
Route::prefix('enrollment')->middleware(['auth', 'verified'])->name('enrollment.')->group(function () {
    Route::get('process', EnrollmentProcess::class)->name('process');
});

// [NUEVO GRUPO] Grupo de Rutas para Egreso y Certificación
Route::prefix('certification')->middleware(['auth', 'verified'])->name('certification.')->group(function () {
    Route::get('certificates', CertificateManager::class)->name('certificates');
    // ... aquí irán pasantías, titulación, etc.
});