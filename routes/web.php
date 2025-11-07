<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Settings\Classrooms\ClassroomManager;

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
    Route::get('classrooms', ClassroomManager::class)->name('classrooms');
});