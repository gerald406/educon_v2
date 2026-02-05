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

    // --- PESTAÑAS (Tabs) ---
    public $activeTab = 'pavilions'; // 'pavilions' o 'classrooms'

    // --- FORMULARIO PABELLÓN ---
    public $pav_name, $pav_location, $pav_id;
    public $isPavilionModalOpen = false;

    // --- FORMULARIO AULA ---
    public $room_pavilion_id, $room_number, $room_capacity, $room_description, $room_id;
    public $isClassroomModalOpen = false;

    public function render()
    {
        return view('livewire.pages.admission.exam.infrastructure-manager', [
            'pavilions' => ExamPavilion::withCount('classrooms')->orderBy('name')->paginate(10, ['*'], 'pavPage'),
            'classrooms' => ExamClassroom::with('pavilion')->orderBy('exam_pavilion_id')->orderBy('room_number')->paginate(10, ['*'], 'roomPage'),
            'allPavilions' => ExamPavilion::where('is_active', true)->orderBy('name')->get() // Para el select de aulas
        ]);
    }

    // --- LÓGICA PABELLONES ---
    public function savePavilion()
    {
        $this->validate([
            'pav_name' => 'required|min:2|max:50',
            'pav_location' => 'nullable|max:100',
        ]);

        ExamPavilion::updateOrCreate(['id' => $this->pav_id], [
            'name' => $this->pav_name,
            'location' => $this->pav_location
        ]);

        $this->isPavilionModalOpen = false;
        $this->reset(['pav_name', 'pav_location', 'pav_id']);
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Guardado']);
    }

    public function editPavilion(ExamPavilion $pavilion)
    {
        $this->pav_id = $pavilion->id;
        $this->pav_name = $pavilion->name;
        $this->pav_location = $pavilion->location;
        $this->isPavilionModalOpen = true;
    }

    public function deletePavilion($id)
    {
        ExamPavilion::find($id)?->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado']);
    }

    // --- LÓGICA AULAS ---
    public function saveClassroom()
    {
        $this->validate([
            'room_pavilion_id' => 'required|exists:exam_pavilions,id',
            'room_number' => 'required|max:10',
            'room_capacity' => 'required|integer|min:1|max:200',
            'room_description' => 'nullable|max:255',
        ]);

        ExamClassroom::updateOrCreate(['id' => $this->room_id], [
            'exam_pavilion_id' => $this->room_pavilion_id,
            'room_number' => $this->room_number,
            'capacity' => $this->room_capacity,
            'description' => $this->room_description
        ]);

        $this->isClassroomModalOpen = false;
        $this->reset(['room_pavilion_id', 'room_number', 'room_capacity', 'room_description', 'room_id']);
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Guardado']);
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

    public function deleteClassroom($id)
    {
        ExamClassroom::find($id)?->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => 'Eliminado']);
    }
}
