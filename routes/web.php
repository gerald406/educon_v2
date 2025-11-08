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
    Route::get('academic-periods', AcademicPeriodManager::class)->name('academic-periods');
    // ... aquí irán carga académica, horarios, etc.
});