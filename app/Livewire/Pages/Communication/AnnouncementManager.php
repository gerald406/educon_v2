<?php

namespace App\Livewire\Pages\Communication;

use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class AnnouncementManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $title = '';
    public $content = '';
    public $announcement_type = 'announcement';
    public $target_audience = 'all';
    public $publish_date = '';
    public $expiration_date = null;
    public $is_featured = false;
    public $status = 'draft';

    // --- PROPIEDADES DE ESTADO ---
    public ?Announcement $editingAnnouncement = null;
    public $isModalOpen = false;
    public $search = '';

    public function mount()
    {
        $this->publish_date = now()->format('Y-m-d\TH:i');
    }

    protected function rules()
    {
        return [
            'title' => 'required|string|max:200',
            'content' => 'required|string',
            'announcement_type' => 'required|in:news,announcement,event,notice,urgent',
            'target_audience' => 'required|in:all,students,teachers',
            'publish_date' => 'required|date',
            'expiration_date' => 'nullable|date|after_or_equal:publish_date',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(Announcement $announcement)
    {
        $this->editingAnnouncement = $announcement;
        $this->fill($announcement);
        $this->publish_date = $announcement->publish_date->format('Y-m-d\TH:i');
        $this->expiration_date = $announcement->expiration_date?->format('Y-m-d\TH:i');
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset();
        $this->mount();
        $this->resetValidation();
    }

    public function save()
    {
        $data = $this->validate();
        $data['published_by_user_id'] = Auth::id();
        
        // Convertir vacíos a null
        $data['expiration_date'] = $data['expiration_date'] === '' ? null : $data['expiration_date'];

        Announcement::updateOrCreate(
            ['id' => $this->editingAnnouncement?->id],
            $data
        );
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Anuncio guardado correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Anuncio?',
            'onConfirmed' => 'deleteAnnouncement'
        ]);
    }

    #[On('deleteAnnouncement')]
    public function deleteAnnouncement(int $id)
    {
        Announcement::findOrFail($id)->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Eliminado!']);
    }

    // --- RENDER ---
    public function render()
    {
        $query = Announcement::with('publishedBy');

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%');
        }
        
        $announcements = $query->orderBy('publish_date', 'desc')->paginate(10);

        return view('livewire.pages.communication.announcement-manager', [
            'announcements' => $announcements,
        ]);
    }
}