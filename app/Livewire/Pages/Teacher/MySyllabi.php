<?php

namespace App\Livewire\Pages\Teacher;

use App\Models\AcademicPeriod;
use App\Models\Syllabus;
use App\Models\TeacherAssignment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads; // <-- [IMPORTANTE] Para subir archivos

#[Layout('layouts.app')]
class MySyllabi extends Component
{
    use WithFileUploads;

    public ?AcademicPeriod $activePeriod = null;
    public $assignments = []; // Cursos asignados al docente

    // --- PROPIEDADES DEL MODAL ---
    public $isModalOpen = false;
    public ?TeacherAssignment $selectedAssignment = null;
    public ?Syllabus $currentSyllabus = null;
    public $pdfUpload; // Propiedad para el archivo PDF

    /**
     * Hook 'mount': Carga el periodo y las asignaciones del docente.
     */
    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $teacher = Auth::user()->teacher;

        if ($teacher && $this->activePeriod) {
            $this->assignments = TeacherAssignment::where('teacher_id', $teacher->id)
                ->where('academic_period_id', $this->activePeriod->id)
                ->with(['didacticUnit', 'syllabus']) // Cargar curso y sílabo
                ->get();
        }
    }

    /**
     * Reglas de validación para la subida.
     */
    protected function rules()
    {
        return [
            'pdfUpload' => 'required|file|mimes:pdf|max:5120', // 5MB Max
        ];
    }
    protected $messages = [
        'pdfUpload.required' => 'Debe seleccionar un archivo PDF.',
        'pdfUpload.mimes' => 'El archivo debe ser un PDF.',
        'pdfUpload.max' => 'El archivo no debe pesar más de 5MB.',
    ];

    /**
     * Abre el modal para una asignación específica.
     */
    public function openSyllabusModal(TeacherAssignment $assignment)
    {
        $this->resetErrorBag(); // Limpiar errores
        $this->selectedAssignment = $assignment;
        // Carga el sílabo si existe, o crea una nueva instancia en memoria
        $this->currentSyllabus = $assignment->syllabus ?? new Syllabus([
            'teacher_assignment_id' => $assignment->id,
            'status' => 'draft',
        ]);
        $this->pdfUpload = null;
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    /**
     * Guarda el archivo PDF subido.
     */
    public function saveSyllabus()
    {
        $this->validate();

        // [INICIO DE LA CORRECCIÓN]
        // Asegurarnos de que el ID de la asignación esté en el objeto
        // antes de guardarlo en la base de datos.
        $this->currentSyllabus->teacher_assignment_id = $this->selectedAssignment->id;
        // [FIN DE LA CORRECCIÓN]

        try {
            // 1. Borrar el archivo PDF anterior si existe
            // (Añadimos comprobación para no borrar si es un registro nuevo)
            if ($this->currentSyllabus->file_url && !$this->currentSyllabus->wasRecentlyCreated) {
                Storage::disk('public')->delete($this->currentSyllabus->file_url);
            }

            // 2. Guardar el nuevo archivo
            $path = $this->pdfUpload->store('syllabi', 'public');

            // 3. Guardar en la base de datos
            $this->currentSyllabus->file_url = $path;
            $this->currentSyllabus->status = 'pending_approval'; // Marcar como pendiente
            $this->currentSyllabus->version = (float)$this->currentSyllabus->version + 0.1; // ej. 1.0 -> 1.1
            
            $this->currentSyllabus->save(); // Ahora el INSERT incluirá el 'teacher_assignment_id'

            $this->dispatch('swal', [
                'icon' => 'success',
                'title' => '¡Éxito!',
                'text' => 'Sílabo subido y enviado para aprobación.',
            ]);

            $this->closeModal();
            $this->mount(); // Recargar la lista de asignaciones

        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => $e->getMessage()]);
        }
    }
    
    /**
     * Permite al docente borrar el PDF subido (si aún está en draft o pendiente).
     */
    public function deleteSyllabus()
    {
        if ($this->currentSyllabus && $this->currentSyllabus->file_url) {
            Storage::disk('public')->delete($this->currentSyllabus->file_url);
            $this->currentSyllabus->update([
                'file_url' => null,
                'status' => 'draft',
            ]);
            $this->dispatch('swal', ['icon' => 'success', 'title' => 'Archivo eliminado']);
            $this->mount(); // Recargar
        }
    }


    public function render()
    {
        return view('livewire.pages.teacher.my-syllabi');
    }
}