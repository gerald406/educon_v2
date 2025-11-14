<?php

namespace App\Livewire\Pages\Teacher;

use App\Models\AcademicPeriod;
use App\Models\Attendance;
use App\Models\Institution;
use App\Models\Registration;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf; // Importar PDF

#[Layout('layouts.app')]
class AttendanceReport extends Component
{
    // --- CONTEXTO ---
    public ?AcademicPeriod $activePeriod = null;
    public ?Teacher $currentTeacher = null;
    public Collection $assignments; // Secciones del docente

    // --- FILTROS ---
    public $selectedAssignmentId = '';
    public $reportDate = ''; // Fecha para el reporte

    // --- DATOS ---
    public Collection $reportData; // Datos combinados para la vista

    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $this->currentTeacher = Auth::user()->teacher;
        $this->reportDate = now()->format('Y-m-d');
        $this->reportData = collect();
        $this->loadAssignments();
    }

    public function loadAssignments()
    {
        if ($this->currentTeacher && $this->activePeriod) {
            $this->assignments = TeacherAssignment::where('teacher_id', $this->currentTeacher->id)
                ->where('academic_period_id', $this->activePeriod->id)
                ->with('didacticUnit', 'shift')
                ->get()
                ->mapWithKeys(fn($a) => 
                    [$a->id => "{$a->didacticUnit->name} (Sec. {$a->section})"]
                );
        } else {
            $this->assignments = collect();
        }
    }

    /**
     * Hook: Se dispara al cambiar la sección o la fecha.
     */
    public function updated()
    {
        $this->loadReportData();
    }

    /**
     * Carga los datos de asistencia para los filtros seleccionados.
     */
    public function loadReportData()
    {
        $this->reportData = collect();
        if (empty($this->selectedAssignmentId) || empty($this->reportDate)) {
            return;
        }

        // 1. Obtener todos los estudiantes (Registrations) de esa sección
        $students = Registration::where('teacher_assignment_id', $this->selectedAssignmentId)
            // [CORRECCIÓN] Usar la ruta de relación correcta
            ->with('enrollment.student.user') 
            ->where('status', 'enrolled')
            ->get()
            // [CORRECCIÓN] Ordenar por la ruta correcta
            ->sortBy('enrollment.student.user.name');
            
        // 2. Obtener los registros de asistencia para esa sección Y esa fecha
        $attendances = Attendance::where('class_date', $this->reportDate)
            ->whereHas('registration', fn($q) => $q->where('teacher_assignment_id', $this->selectedAssignmentId))
            ->get()
            ->keyBy('registration_id'); // Indexar por ID de inscripción

        // 3. Mapear los datos
        $this->reportData = $students->map(function ($registration) use ($attendances) {
            $attendanceRecord = $attendances->get($registration->id);
            
            $statusKey = 'no-data';
            $statusText = 'Sin Registro';

            if ($attendanceRecord) {
                $statusKey = $attendanceRecord->attendance_type;
                $statusText = $this->getStatusLabel($statusKey);
            } elseif (now()->parse($this->reportDate)->isFuture()) {
                $statusKey = 'scheduled';
                $statusText = 'Programado';
            }

            // [CORRECCIÓN] Usar las rutas de relación correctas
            $student = $registration->enrollment?->student;
            $user = $student?->user;

            return [
                'code' => $student?->code ?? 'N/A',
                'name' => $user?->name ?? 'Usuario no encontrado',
                'status_key' => $statusKey,
                'status_text' => $statusText,
            ];
        });
    }
    
    private function getStatusLabel($status): string
    {
        return [
            'present' => 'Presente',
            'absent' => 'Ausente',
            'late' => 'Tardanza',
            'justified' => 'Justificado',
        ][$status] ?? 'Sin Registro';
    }

    /**
     * Genera y descarga el reporte en PDF.
     */
    public function generatePdf()
    {
        if ($this->reportData->isEmpty()) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No hay datos que reportar.']);
            return;
        }
        
        $institution = Institution::first();
        $assignment = TeacherAssignment::with('didacticUnit', 'shift')->find($this->selectedAssignmentId);

        // Lógica del Logo Base64 (de Fase 71)
        $logoData = null;
        if ($institution?->logo_url && Storage::disk('public')->exists($institution->logo_url)) {
            $path = Storage::disk('public')->path($institution->logo_url);
            $fileContent = file_get_contents($path);
            $mime = mime_content_type($path);
            $logoData = 'data:' . $mime . ';base64,' . base64_encode($fileContent);
        }

        $data = [
            'reportData' => $this->reportData,
            'activePeriod' => $this->activePeriod,
            'institution' => $institution,
            'logoData' => $logoData,
            'assignment' => $assignment,
            'teacher' => $this->currentTeacher,
            'reportDate' => $this->reportDate,
        ];

        $pdf = Pdf::loadView('reports.attendance-list-pdf', $data);
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'reporte-asistencia-' . $assignment->didacticUnit->code . '-' . $this->reportDate . '.pdf');
    }

    // En App\Livewire\Pages\Teacher\AttendanceReport.php
    public function getStatusClass($status): string
    {
        return match($status) {
            'present' => 'text-green-600',
            'absent' => 'text-red-600 font-semibold',
            'late' => 'text-orange-600',
            'justified' => 'text-blue-600',
            'no-data', 'scheduled' => 'text-gray-500',
            default => ''
        };
    }

    public function render()
    {
        return view('livewire.pages.teacher.attendance-report');
    }
}