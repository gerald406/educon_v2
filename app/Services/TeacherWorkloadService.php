<?php

namespace App\Services;

use App\Models\AcademicPeriod;
use App\Models\DidacticUnit;
use App\Models\Teacher;
use App\Models\TeacherAssignment;

class TeacherWorkloadService
{
    // Definimos las horas mínimas y máximas
    const MIN_WEEKLY_HOURS = 18;
    const MAX_WEEKLY_HOURS = 26;

    /**
     * Calcula el total de horas semanales asignadas a un docente en un periodo.
     * [LÓGICA CORREGIDA]
     *
     * @param Teacher $teacher
     * @param AcademicPeriod $period
     * @return int
     */
    public function calculateWeeklyHours(Teacher $teacher, AcademicPeriod $period): int
    {
        // 1. Obtener TODAS las asignaciones (secciones) del docente en el periodo
        $assignments = TeacherAssignment::where('teacher_id', $teacher->id)
            ->where('academic_period_id', $period->id)
            ->with('didacticUnit') // Cargar la unidad para obtener sus horas
            ->get();

        // 2. Sumar las 'weekly_hours' de cada asignación
        // Esto suma correctamente 8h + 6h + 8h = 22h
        return $assignments->sum(function ($assignment) {
            return $assignment->didacticUnit->weekly_hours ?? 0;
        });
    }

    /**
     * Verifica si añadir un nuevo curso excedería la carga máxima.
     * [LÓGICA CORREGIDA]
     *
     * @param Teacher $teacher
     * @param AcademicPeriod $period
     * @param DidacticUnit $newUnit (La unidad que se *quiere* asignar)
     * @param TeacherAssignment|null $editingAssignment (Para excluirla del cálculo si se edita)
     * @return bool
     */
    public function wouldExceedMaxHours(Teacher $teacher, AcademicPeriod $period, DidacticUnit $newUnit, ?TeacherAssignment $editingAssignment = null): bool
    {
        // 1. Obtener todas las asignaciones existentes, EXCEPTO la que estamos editando
        $query = TeacherAssignment::where('teacher_id', $teacher->id)
            ->where('academic_period_id', $period->id);

        if ($editingAssignment) {
            $query->where('id', '!=', $editingAssignment->id);
        }
        
        $assignments = $query->with('didacticUnit')->get();

        // 2. Calcular las horas actuales sumando cada asignación
        $currentHours = $assignments->sum(function ($assignment) {
            return $assignment->didacticUnit->weekly_hours ?? 0;
        });

        // 3. Añadir las horas del nuevo curso
        $newTotalHours = $currentHours + $newUnit->weekly_hours;

        // 4. Comprobar si excede el máximo
        return $newTotalHours > self::MAX_WEEKLY_HOURS;
    }
}