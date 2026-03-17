<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\Career;
use App\Models\Schedule;
use App\Models\Shift;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeacherScheduleExport;
use App\Exports\CareerScheduleExport;

class ScheduleReportController extends Controller
{
    /**
     * Genera y descarga reportes de horarios
     * Solo admite 2 tipos: teacher y career
     */
    public function downloadPDF(Request $request)
    {
        // Validación
        $request->validate([
            'type' => 'required|in:teacher,career',
            'format' => 'required|in:pdf,excel',
            'id' => 'required|integer',
            'period_id' => 'required|exists:academic_periods,id',
            // Validaciones condicionales para carrera
            'semester' => 'required_if:type,career|string',
            'shift_id' => 'required_if:type,career|exists:shifts,id',
        ]);

        $type = $request->query('type');
        $format = $request->query('format');
        $id = $request->query('id');
        $periodId = $request->query('period_id');
        $semester = $request->query('semester');
        $shiftId = $request->query('shift_id');

        $period = AcademicPeriod::findOrFail($periodId);

        // Delegar según formato
        if ($format === 'excel') {
            return $this->generateExcel($type, $id, $period, $semester, $shiftId);
        }

        return $this->generatePDF($type, $id, $period, $semester, $shiftId);
    }

    /**
     * Generar PDF según tipo
     */
    private function generatePDF($type, $id, $period, $semester, $shiftId)
    {
        return match ($type) {
            'teacher' => $this->generateTeacherPDF($id, $period),
            'career' => $this->generateCareerPDF($id, $period, $semester, $shiftId),
        };
    }

    /**
     * Generar Excel según tipo
     */
    private function generateExcel($type, $id, $period, $semester, $shiftId)
    {
        return match ($type) {
            'teacher' => $this->generateTeacherExcel($id, $period),
            'career' => $this->generateCareerExcel($id, $period, $semester, $shiftId),
        };
    }

    // ==========================================
    // REPORTE POR DOCENTE - PDF
    // ==========================================

    private function generateTeacherPDF($teacherId, $period)
    {
        $teacher = Teacher::with('user')->findOrFail($teacherId);

        // Obtener asignaciones del docente
        $assignments = TeacherAssignment::with([
            'didacticUnit.module.studyPlan.career',
            'shift',
            'schedules.classroomResource'
        ])
            ->where('teacher_id', $teacherId)
            ->where('academic_period_id', $period->id)
            ->where('status', 'active')
            ->get();

        // Obtener todos los horarios
        $schedules = Schedule::with([
            'teacherAssignment.didacticUnit',
            'teacherAssignment.shift',
            'classroomResource'
        ])
            ->whereHas(
                'teacherAssignment',
                fn($q) =>
                $q->where('teacher_id', $teacherId)
                    ->where('academic_period_id', $period->id)
                    ->where('status', 'active')
            )
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')")
            ->orderBy('start_time')
            ->get();

        // Agrupar por día
        $groupedSchedules = $schedules->groupBy('day_of_week');

        // Calcular estadísticas
        $totalHours = $schedules->sum(function ($schedule) {
            return $schedule->start_time->diffInMinutes($schedule->end_time) / 60;
        });

        $data = [
            'title' => 'Carga Horaria Docente',
            'subtitle' => $teacher->user->name . ' ' . $teacher->user->lastname,
            'period' => $period,
            'teacher' => $teacher,
            'assignments' => $assignments,
            'schedules' => $schedules,
            'groupedSchedules' => $groupedSchedules,
            'totalHours' => $totalHours,
            'totalCourses' => $assignments->count(),
        ];

        $pdf = Pdf::loadView('reports.schedules.teacher-report', $data)
            ->setPaper('a4', 'landscape');

        $filename = "Horario_Docente_" . str_replace(' ', '_', $teacher->user->lastname) . ".pdf";

        return $pdf->stream($filename);
    }

    // ==========================================
    // REPORTE POR DOCENTE - EXCEL
    // ==========================================

    private function generateTeacherExcel($teacherId, $period)
    {
        $teacher = Teacher::with('user')->findOrFail($teacherId);

        $filename = "Horario_Docente_" . str_replace(' ', '_', $teacher->user->lastname) . ".xlsx";

        return Excel::download(
            new TeacherScheduleExport($teacherId, $period->id),
            $filename
        );
    }

    // ==========================================
    // REPORTE CONSOLIDADO POR PROGRAMA - PDF
    // ==========================================
    private function generateCareerPDF($careerId, $period, $semester, $shiftId)
    {
        $career = Career::findOrFail($careerId);
        $shift = Shift::findOrFail($shiftId);

        // Consulta filtrada por programa, semestre y turno
        $assignments = TeacherAssignment::with([
            'didacticUnit',
            'teacher.user',
            'shift',
            'schedules.classroomResource'
        ])
            ->where('academic_period_id', $period->id)
            ->where('status', 'active')
            ->where('shift_id', $shiftId)
            ->whereHas(
                'didacticUnit',
                fn($q) => $q->where('semester', $semester)
            )
            ->whereHas(
                'didacticUnit.module.studyPlan',
                fn($q) => $q->where('career_id', $careerId)
            )
            ->get();

        // Obtener todos los horarios
        $schedules = Schedule::with([
            'teacherAssignment.didacticUnit',
            'teacherAssignment.teacher.user',
            'teacherAssignment.shift',
            'classroomResource'
        ])
            ->whereHas('teacherAssignment', function ($q) use ($careerId, $period, $semester, $shiftId) {
                $q->where('academic_period_id', $period->id)
                    ->where('status', 'active')
                    ->where('shift_id', $shiftId)
                    ->whereHas(
                        'didacticUnit',
                        fn($sq) => $sq->where('semester', $semester)
                    )
                    ->whereHas(
                        'didacticUnit.module.studyPlan',
                        fn($sq) => $sq->where('career_id', $careerId)
                    );
            })
            ->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday')")
            ->orderBy('start_time')
            ->get();

        // Agrupar por día
        $groupedSchedules = $schedules->groupBy('day_of_week');

        // ESTA ES LA VARIABLE QUE FALTABA
        $assignmentsBySemester = $assignments->groupBy(function ($assignment) {
            return $assignment->didacticUnit->semester;
        })->sortKeys();

        $data = [
            'title' => 'Horario Consolidado',
            'subtitle' => $career->name . ' - Semestre ' . $semester . ' - Turno ' . $shift->name,
            'period' => $period,
            'career' => $career,
            'shift' => $shift,
            'semester' => $semester,
            'assignments' => $assignments,
            'assignmentsBySemester' => $assignmentsBySemester, // ← AGREGADA AQUÍ
            'schedules' => $schedules,
            'groupedSchedules' => $groupedSchedules,
            'totalSections' => $assignments->count(),
            'totalTeachers' => $assignments->pluck('teacher_id')->unique()->count(),
        ];

        $pdf = Pdf::loadView('reports.schedules.career-report', $data)
            ->setPaper('a4', 'landscape');

        $filename = "Horario_" . str_replace(' ', '_', $career->name) . "_Sem{$semester}_" . str_replace(' ', '_', $shift->name) . ".pdf";

        return $pdf->stream($filename);
    }

    // ==========================================
    // REPORTE CONSOLIDADO POR PROGRAMA - EXCEL
    // ==========================================

    private function generateCareerExcel($careerId, $period, $semester, $shiftId)
    {
        $career = Career::findOrFail($careerId);
        $shift = Shift::findOrFail($shiftId);

        $filename = "Horario_" . str_replace(' ', '_', $career->name) . "_Sem{$semester}.xlsx";

        return Excel::download(
            new CareerScheduleExport($careerId, $period->id, $semester, $shiftId),
            $filename
        );
    }
}
