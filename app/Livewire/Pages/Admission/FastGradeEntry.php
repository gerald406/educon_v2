<?php

namespace App\Livewire\Pages\Admission;

use App\Models\Applicant;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class FastGradeEntry extends Component
{
    // --- BÚSQUEDA ---
    public $searchDni = '';

    // --- MODAL Y DATOS ---
    public $isModalOpen = false;
    public ?Applicant $applicant = null;
    public ?User $applicantUser = null;
    public $examScore = '';

    // --- MENSAJES ---
    public $notFoundMessage = '';

    /**
     * Busca al postulante por DNI (usuario).
     */
    public function search()
    {
        $this->reset('notFoundMessage', 'applicant', 'applicantUser', 'examScore');

        $this->validate([
            'searchDni' => 'required|digits:8'
        ]);

        // 1. Buscar usuario por DNI
        $user = User::where('document_number', $this->searchDni)->first();

        if (!$user) {
            $this->notFoundMessage = "No existe usuario con DNI {$this->searchDni}.";
            $this->dispatch('focus-search'); // Devolver foco
            return;
        }

        // 2. Buscar perfil de postulante
        $this->applicant = $user->applicant;

        if (!$this->applicant) {
            $this->notFoundMessage = "El usuario {$user->name} existe, pero NO está registrado como postulante.";
            $this->dispatch('focus-search');
            return;
        }

        // 3. Todo OK: Cargar datos y abrir modal
        $this->applicantUser = $user;
        $this->examScore = $this->applicant->exam_score; // Cargar nota actual si existe
        $this->isModalOpen = true;

        // Poner foco en el input de nota al abrir el modal
        $this->dispatch('focus-score');
    }

    /**
     * Guarda la nota y reinicia el flujo.
     */
    public function saveGrade()
    {
        $this->validate([
            'examScore' => 'required|numeric|min:0|max:60' // Asumiendo escala 0-20 o la que uses
        ]);

        if ($this->applicant) {
            $this->applicant->update([
                'exam_score' => $this->examScore,
                // Opcional: cambiar estado a 'evaluated'
                'application_status' => 'evaluado' // o 'evaluado' según tu DB
            ]);

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Guardado!',
                'text' => "Nota registrada para {$this->applicantUser->name}",
                'timer' => 1500,
                'showConfirmButton' => false
            ]);
        }

        $this->closeModal();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset('searchDni', 'applicant', 'applicantUser', 'examScore');

        // [CLAVE] Devolver el foco al input de búsqueda inmediatamente
        $this->dispatch('focus-search');
    }

    public function render()
    {
        return view('livewire.pages.admission.fast-grade-entry');
    }
}
