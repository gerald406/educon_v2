<?php

namespace App\Livewire\Pages\Admission\Exam;

use App\Models\ExamClassroom;
use App\Models\ExamPavilion;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class InfrastructureManager extends Component
{
    use WithPagination;

    // --- PESTAÑAS ---
    public $activeTab = 'pavilions';

    // --- FORMULARIO PABELLÓN ---
    public $pav_name = '';
    public $pav_location = '';
    public $pav_id = null;
    public $isPavilionModalOpen = false;

    // --- FORMULARIO AULA ---
    public $room_pavilion_id = '';
    public $room_number = '';
    public $room_capacity = 40;
    public $room_description = '';
    public $room_id = null;
    public $isClassroomModalOpen = false;

    // --- CAMBIAR PESTAÑA ---
    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage('pavPage');
        $this->resetPage('roomPage');
    }

    public function render()
    {
        return view('livewire.pages.admission.exam.infrastructure-manager', [
            'pavilions' => ExamPavilion::withCount('classrooms')
                ->orderBy('name')
                ->paginate(10, ['*'], 'pavPage'),

            'classrooms' => ExamClassroom::with('pavilion')
                ->when($this->activeTab === 'classrooms', function ($q) {
                    // Optimización: Solo cargar aulas si estamos en esa pestaña
                    $q->orderBy('exam_pavilion_id')->orderBy('room_number');
                })
                ->paginate(10, ['*'], 'roomPage'),

            'allPavilions' => ExamPavilion::where('is_active', true)->orderBy('name')->get()
        ]);
    }

    // ==========================================
    // LÓGICA PABELLONES
    // ==========================================

    public function createPavilion()
    {
        $this->reset(['pav_id', 'pav_name', 'pav_location']);
        $this->isPavilionModalOpen = true;
    }

    public function editPavilion(ExamPavilion $pavilion)
    {
        $this->pav_id = $pavilion->id;
        $this->pav_name = $pavilion->name;
        $this->pav_location = $pavilion->location;
        $this->isPavilionModalOpen = true;
    }

    public function savePavilion()
    {
        $this->validate([
            'pav_name' => 'required|min:2|max:50',
            'pav_location' => 'nullable|max:100',
        ]);

        ExamPavilion::updateOrCreate(
            ['id' => $this->pav_id],
            [
                'name' => $this->pav_name,
                'location' => $this->pav_location,
                'is_active' => true
            ]
        );

        $this->isPavilionModalOpen = false;
        $this->reset(['pav_name', 'pav_location', 'pav_id']);
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Pabellón Guardado']);
    }

    public function deletePavilion($id)
    {
        $pavilion = ExamPavilion::find($id);
        if ($pavilion) {
            $pavilion->delete();
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Pabellón Eliminado']);
        }
    }

    // ==========================================
    // LÓGICA AULAS
    // ==========================================

    public function createClassroom()
    {
        $this->reset(['room_id', 'room_pavilion_id', 'room_number', 'room_description']);
        $this->room_capacity = 40;

        // Pre-seleccionar el primer pabellón si existe
        $firstPavilion = ExamPavilion::first();
        if ($firstPavilion) {
            $this->room_pavilion_id = $firstPavilion->id;
        }

        $this->isClassroomModalOpen = true;
    }

    public function editClassroom(ExamClassroom $room)
    {
        $this->room_id = $room->id;
        $this->room_pavilion_id = $room->exam_pavilion_id;
        $this->room_number = $room->room_number;
        $this->room_capacity = $room->capacity;
        $this->room_description = $room->description;
        $this->isClassroomModalOpen = true;
    }

    public function saveClassroom()
    {
        $this->validate([
            'room_pavilion_id' => 'required|exists:exam_pavilions,id',
            'room_number' => 'required|max:10',
            'room_capacity' => 'required|integer|min:1|max:200',
            'room_description' => 'nullable|max:255',
        ]);

        ExamClassroom::updateOrCreate(
            ['id' => $this->room_id],
            [
                'exam_pavilion_id' => $this->room_pavilion_id,
                'room_number' => $this->room_number,
                'capacity' => $this->room_capacity,
                'description' => $this->room_description,
                'is_active' => true
            ]
        );

        $this->isClassroomModalOpen = false;
        $this->reset(['room_pavilion_id', 'room_number', 'room_capacity', 'room_description', 'room_id']);
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Aula Guardada']);
    }

    public function deleteClassroom($id)
    {
        ExamClassroom::find($id)?->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Aula Eliminada']);
    }
}
