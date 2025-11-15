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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;

#[Layout('layouts.app')]
class CumulativeAttendanceReport extends Component
{
    // --- CONTEXTO ---
    public ?AcademicPeriod $activePeriod = null;
    public ?Teacher $currentTeacher = null;
    public Collection $assignments; // Secciones del docente

    // --- FILTROS ---
    public $selectedAssignmentId = '';
    public $startDate = '';
    public $endDate = '';

    // --- DATOS ---
    public Collection $reportData; // Datos combinados para la vista

    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $this->currentTeacher = Auth::user()->teacher;

        // Fechas por defecto: desde el inicio de clases del periodo hasta hoy
        $this->startDate = $this->activePeriod?->classes_start_date?->format('Y-m-d') ?? now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');

        $this->reportData = collect();
        $this->loadAssignments();
    }

    public function loadAssignments()
    {
        if ($this-> currentTeacher && $this->activePeriod) {
            $this->assignments = TeacherAssignment::where('teacher_id', $this->currentTeacher->id)
                ->where('academic_period_id', $this->activePeriod->id)
                ->with('didacticUnit', 'shift')
                ->get()
                ->mapWithKeys(
                    fn($a) =>
                    [$a->id => "{$a->didacticUnit->name} (Sec. {$a->section})"]
                );
        } else {
            $this->assignments = collect();
        }
    }

    /**
     * Hook: Se dispara al cambiar la sección o las fechas.
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
        $this-> reportData = collect();
        if (empty($this->selectedAssignmentId) || empty($this->startDate) || empty($this->endDate)) {
            return;
        }

        // 1. Obtener todos los estudiantes (Registrations) de esa sección
        $students = Registration::where('teacher_assignment_id', $this->selectedAssignmentId)
            ->with('enrollment.student.user')
            ->where('status', 'enrolled')
            ->get()
            ->sortBy('enrollment.student.user.name');

        // 2. Obtener TODOS los registros de asistencia para esa sección en el RANGO de fechas
        $attendances = Attendance::where('class_date', '>=', $this->startDate)
            ->where('class_date', '<=', $this->endDate)
            ->whereHas('registration', fn($q) => $q->where('teacher_assignment_id', $this->selectedAssignmentId))
            ->select('registration_id', 'attendance_type', DB::raw('COUNT(*) as total'))
            ->groupBy('registration_id', 'attendance_type')
            ->get();

        // 3. Mapear los datos
        $this->reportData = $students->map(function ($registration) use ($attendances) {

            $studentAttendances = $attendances->where('registration_id', $registration->id);

            $present = $studentAttendances->where('attendance_type', 'present')->first()?->total ?? 0;
            $late = $studentAttendances->where('attendance_type', 'late')->first()?->total ?? 0;
            $absent = $studentAttendances->where('attendance_type', 'absent')->first()?->total ?? 0;
            $justified = $studentAttendances->where('attendance_type', 'justified')->first()?->total ?? 0;

            // Total de asistencias "válidas" (presente, tarde, justificado)
            $validAssist = $present + $late + $justified;
            // Total de sesiones registradas
            $totalSessions = $present + $late + $absent + $justified;

            $percentage = ($totalSessions > 0) ? ($validAssist / $totalSessions) * 100 : 100;

            $student = $registration->enrollment?->student;
            $user = $student?->user;

            return [
                'code' => $student?->code ?? 'N/A',
                'name' => $user?->name ?? 'Usuario no encontrado',
                'present' => $present,
                'late' => $late,
                'absent' => $absent,
                'justified' => $justified,
                'percentage' => $percentage,
            ];
        });
    }

    /**
     * Genera y descarga el reporte en PDF.
     */
    public function generatePdf()
    {
        if ($this-> reportData->isEmpty()) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No hay datos que reportar.']);
            return;
        }

        $institution = Institution::first();
        $assignment = TeacherAssignment::with('didacticUnit', 'shift')->find($this->selectedAssignmentId);

        // Lógica del Logo Base64
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
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ];

        $pdf = Pdf::loadView('reports.cumulative-attendance-pdf', $data);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'reporte-asistencia-acumulada-' . $assignment->didacticUnit->code . '.pdf');
    }

    public function render()
    {
        return view('livewire.pages.teacher.cumulative-attendance-report');
    }
}
