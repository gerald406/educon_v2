<?php

namespace App\Livewire\Pages\Services\Library;

use App\Models\Career;
use App\Models\Institution;
use App\Models\LibraryResource;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class LibraryResourceManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $institution_id = '';
    public $career_id = null;
    public $code = '';
    public $title = '';
    public $author = '';
    public $resource_type = 'book';
    public $publisher = '';
    public $publication_year = '';
    public $isbn = '';
    public $copies_available = 1;
    public $physical_location = '';
    public $status = 'available';

    // --- PROPIEDADES DE ESTADO ---
    public ?LibraryResource $editingResource = null;
    public $isModalOpen = false;
    public $search = '';

    // --- DATOS PARA DROPDOWNS ---
    public Collection $careers;

    /**
     * Hook 'mount': Carga la institución y las carreras.
     */
    public function mount()
    {
        // Asumimos que trabajamos con la primera institución
        $this->institution_id = Institution::first()->id;
        $this->careers = Career::where('status', 'active')->pluck('name', 'id');
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'institution_id' => 'required|exists:institutions,id',
            'career_id' => 'nullable|exists:careers,id',
            'title' => 'required|string|max:300',
            'author' => 'nullable|string|max:200',
            'resource_type' => 'required|in:book,magazine,thesis,manual,digital,audiovisual',
            'publisher' => 'nullable|string|max:200',
            'publication_year' => 'nullable|integer|digits:4|min:1900|max:' . date('Y'),
            'isbn' => 'nullable|string|max:20',
            'copies_available' => 'required|integer|min:0',
            'physical_location' => 'nullable|string|max:100',
            'status' => 'required|in:available,borrowed,reserved,maintenance,lost',
            'code' => [
                'required', 'string', 'max:50',
                Rule::unique('library_resources')->ignore($this->editingResource?->id)
            ],
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(LibraryResource $resource)
    {
        $this->editingResource = $resource;
        $this->fill($resource);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->resetExcept('careers', 'institution_id');
        $this->resetValidation();
    }

    public function save()
    {
        $data = $this->validate();
        
        // Convertir campos opcionales vacíos a null
        $data['career_id'] = $data['career_id'] === '' ? null : $data['career_id'];
        
        $model = $this->editingResource ?? new LibraryResource();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Recurso de biblioteca guardado correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Recurso?',
            'text' => 'Esto eliminará el recurso del catálogo.',
            'onConfirmed' => 'deleteResource'
        ]);
    }

    #[On('deleteResource')]
    public function deleteResource(int $id)
    {
        LibraryResource::findOrFail($id)->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Eliminado!']);
    }

    // --- RENDER ---
    public function render()
    {
        $query = LibraryResource::with('career');

        if ($this->search) {
            $query->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('author', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
        }
        
        $resources = $query->orderBy('title')->paginate(10);

        return view('livewire.pages.services.library.library-resource-manager', [
            'resources' => $resources,
        ]);
    }
}