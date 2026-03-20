<?php

namespace App\Http\Controllers;

use App\Models\ExamClassroom;
use App\Models\ExamClassroomAssignment;
use App\Models\Applicant;
use Illuminate\Http\Request;

class ExamAttendanceController extends Controller
{
    /**
     * Vista pública de control de ingreso.
     */
    public function index()
    {
        return view('admission.exam.attendance');
    }

    /**
     * Búsqueda pública por DNI — sin auth.
     */
    public function search(Request $request)
    {
        $request->validate(['dni' => 'required|digits:8']);

        $applicant = Applicant::with([
            'user',
            'admissionOffering.career',
            'admissionOffering.shift',
            'examAssignment.classroom.pavilion',
        ])
            ->whereHas('user', fn($q) => $q->where('document_number', $request->dni))
            ->first();

        if (!$applicant) {
            return response()->json([
                'status'  => 'error',
                'message' => 'No se encontró ningún postulante con ese DNI.',
            ]);
        }

        $assignment = $applicant->examAssignment;

        if (!$assignment) {
            return response()->json([
                'status'  => 'error',
                'message' => 'El postulante no tiene aula asignada aún.',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data'   => [
                'assignment_id' => $assignment->id,
                'dni'           => $applicant->user->document_number,
                'nombres'       => $applicant->user->name,
                'apellidos'     => $applicant->user->lastname,
                'programa'      => $applicant->admissionOffering?->career?->name ?? 'Sin asignar',
                'turno'         => $applicant->admissionOffering?->shift?->name ?? 'Sin turno',
                'pabellon'      => $assignment->classroom?->pavilion?->name ?? 'Sin asignar',
                'aula'          => $assignment->classroom?->room_number ?? 'Sin asignar',
                'ya_registro'   => !is_null($assignment->attended_at),
                'attended_at'   => $assignment->attended_at?->format('d/m/Y H:i:s'),
            ],
        ]);
    }

    /**
     * Registro de ingreso público — sin auth.
     */
    public function register(ExamClassroomAssignment $assignment)
    {
        if ($assignment->attended_at) {
            return response()->json([
                'status'  => 'warning',
                'message' => 'Este postulante ya registró su ingreso a las ' .
                    $assignment->attended_at->format('H:i:s') . '.',
            ]);
        }

        $assignment->update(['attended_at' => now()]);

        return response()->json([
            'status'      => 'success',
            'message'     => 'Ingreso registrado correctamente.',
            'attended_at' => $assignment->attended_at->format('d/m/Y H:i:s'),
        ]);
    }

    /**
     * Reporte protegido — solo staff con permiso.
     */
    public function report()
    {
        $classrooms = ExamClassroom::with([
            'pavilion',
            'assignments.applicant.user',
            'assignments.applicant.admissionOffering.career',
        ])
            ->withCount([
                'assignments',
                'assignments as attended_count' => fn($q) => $q->whereNotNull('attended_at'),
                'assignments as absent_count'   => fn($q) => $q->whereNull('attended_at'),
            ])
            ->orderBy('exam_pavilion_id')
            ->orderBy('room_number')
            ->get();

        return view('admission.exam.attendance-report', compact('classrooms'));
    }
}
