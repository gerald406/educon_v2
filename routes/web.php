<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Settings\Classrooms\ClassroomManager;
use App\Livewire\Pages\Settings\PaymentConcepts\PaymentConceptManager;
use App\Livewire\Pages\Academic\Careers\CareerManager;

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
});

// [NUEVO GRUPO] Grupo de Rutas para Gestión Académica
Route::prefix('academic')->middleware(['auth', 'verified'])->name('academic.')->group(function () {
    Route::get('careers', CareerManager::class)->name('careers');
    // ... aquí irán planes, módulos, cursos, etc.
});