<?php

namespace App\Livewire\Pages\Certification;

use App\Models\Certificate;
use App\Models\Module;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class CertificateManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DEL FORMULARIO ---
    public $student_id = '';
    public $certificate_type = 'studies';
    public $module_id = null; // Solo para tipo 'modular'
    public $code = '';
    public $issue_date = '';
    public $status = 'valid';

    // --- PROPIEDADES DE ESTADO ---
    public ?Certificate $editingCertificate = null;
    public $isModalOpen = false;
    public $search = ''; // Búsqueda de certificados emitidos
    public $studentSearch = ''; // Búsqueda de estudiante para el modal

    // --- DATOS PARA DROPDOWNS ---
    public Collection $students;
    public Collection $modules;

    /**
     * Hook 'mount': Carga la fecha actual.
     */
    public function mount()
    {
        $this->issue_date = now()->format('Y-m-d');
        $this->students = collect();
        $this->modules = collect();
    }

    /**
     * Hook: Busca estudiantes cuando se escribe en el modal.
     */
    public function updatedStudentSearch($value)
    {
        if (strlen($value) < 3) {
            $this->students = collect();
            return;
        }
        $this->students = Student::with('user', 'career')
            ->whereHas('user', fn($q) => $q->where('name', 'like', '%'.$value.'%'))
            ->orWhere('code', 'like', '%' . $value . '%')
            ->take(5)
            ->get();
    }

    /**
     * Hook: Carga los módulos si el tipo es 'modular'.
     */
    public function updatedCertificateType($value)
    {
        if ($value == 'modular' && $this->student_id) {
            $student = Student::find($this->student_id);
            $this->modules = Module::where('study_plan_id', $student->study_plan_id)
                            ->pluck('name', 'id');
        } else {
            $this->modules = collect();
            $this->module_id = null;
        }
    }

    /**
     * [NUEVO] Acción para seleccionar un estudiante de la lista.
     */
    public function selectStudent(int $studentId)
    {
        $student = Student::with('user')->find($studentId);
        if ($student) {
            $this->student_id = $student->id;
            // Pone el nombre/código en el input de búsqueda
            $this->studentSearch = $student->user->name . ' (' . $student->code . ')'; 
            // Cierra el dropdown de resultados
            $this->students = collect(); 
            
            // Llama al hook 'updatedCertificateType' por si el tipo 'modular' ya estaba seleccionado
            $this->updatedCertificateType($this->certificate_type);
        }
    }

    /**
     * Define las reglas de validación.
     */
    protected function rules()
    {
        return [
            'student_id' => 'required|exists:students,id',
            'certificate_type' => 'required|in:modular,grades,studies,graduation',
            'module_id' => 'required_if:certificate_type,modular|nullable|exists:modules,id',
            'code' => 'required|string|max:50|unique:certificates,code' . ($this->editingCertificate ? ',' . $this->editingCertificate->id : ''),
            'issue_date' => 'required|date',
            'status' => 'required|in:valid,cancelled,expired',
        ];
    }

    // --- ACCIONES DEL CRUD ---

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function openEditModal(Certificate $certificate)
    {
        $this->editingCertificate = $certificate;
        $this->fill($certificate);
        $this->issue_date = $certificate->issue_date->format('Y-m-d');
        // Cargar el estudiante para el dropdown
        $this->students = Student::with('user')->where('id', $certificate->student_id)->get();
        $this->studentSearch = $certificate->student->user->name;
        // Cargar módulos si es necesario
        $this->updatedCertificateType($this->certificate_type);

        // [LÍNEA CORREGIDA] Añadir esta línea
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false; // [CORREGIDO]
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset();
        $this->mount(); // Recarga los valores por defecto
        $this->resetValidation();
        
        // Añade estas dos líneas para limpiar la búsqueda
        $this->studentSearch = '';
        $this->students = collect();
    }

    public function save()
    {
        $data = $this->validate();
        $data['issued_by_user_id'] = Auth::id(); // Asigna al usuario logueado
        
        $model = $this->editingCertificate ?? new Certificate();
        $model->fill($data);
        $model->save();
        
        $this->closeModal();
        $this->dispatch('swal', [
            'icon' => 'success',
            'title' => '¡Hecho!',
            'text' => 'Certificado registrado correctamente.',
        ]);
    }

    public function confirmDelete(int $id)
    {
        $this->dispatch('swal:confirm', [
            'id' => $id,
            'title' => '¿Eliminar Registro?',
            'text' => 'Esto eliminará el registro del certificado (no el archivo).',
            'onConfirmed' => 'deleteCertificate'
        ]);
    }

    #[On('deleteCertificate')]
    public function deleteCertificate(int $id)
    {
        Certificate::findOrFail($id)->delete();
        $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Eliminado!']); // [CORREGIDO]
    }

    // --- RENDER ---
    public function render()
    {
        $query = Certificate::with(['student.user', 'module', 'issuedBy']);

        if ($this->search) {
            $query->where('code', 'like', '%' . $this->search . '%')
                  ->orWhereHas('student.user', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'));
        }
        
        $certificates = $query->orderBy('issue_date', 'desc')->paginate(10);

        return view('livewire.pages.certification.certificate-manager', [
            'certificates' => $certificates,
        ]);
    }
}