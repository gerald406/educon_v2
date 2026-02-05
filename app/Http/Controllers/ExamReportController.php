<?php

namespace App\Http\Controllers;

use App\Models\ExamClassroom;
use App\Models\ExamClassroomAssignment;
use Barryvdh\DomPDF\Facade\Pdf;

class ExamReportController extends Controller
{
    public function doorList(ExamClassroom $classroom)
    {
        $assignments = ExamClassroomAssignment::with(['applicant.user', 'applicant.admissionOffering.career'])
            ->where('exam_classroom_id', $classroom->id)
            ->join('applicants', 'exam_classroom_assignments.applicant_id', '=', 'applicants.id')
            ->join('users', 'applicants.user_id', '=', 'users.id')
            ->orderBy('users.lastname') // Orden alfabético para la lista
            ->select('exam_classroom_assignments.*') // Evitar conflictos de ID
            ->get();

        $pdf = Pdf::loadView('reports.exam.door-list', [
            'classroom' => $classroom,
            'assignments' => $assignments
        ]);

        return $pdf->stream('Lista_Aula_' . $classroom->room_number . '.pdf');
    }
}
