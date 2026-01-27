<?php

namespace App\Services;

use App\Models\AcademicRecord;
use App\Models\DidacticUnit;
use App\Models\Student;
use App\Models\SystemSetting;
use App\Models\TeacherAssignment;
use App\Models\Voucher;
use App\Models\VoucherSeries; // [NUEVO] Importar modelo
use Illuminate\Support\Collection;

class EnrollmentService
{
    /**
     * Obtiene la propuesta de cursos.
     */
    public function getEnrollmentProposal(Student $student, int $nextSemester, int $activePeriodId): array
    {
        $minGrade = SystemSetting::where('key_name', 'minimum_passing_grade')->value('value') ?? 13;

        // 1. Cursos a Cargo (Jalados)
        $failedUnitIds = AcademicRecord::where('student_id', $student->id)
            ->select('didactic_unit_id')
            ->groupBy('didactic_unit_id')
            ->havingRaw('MAX(final_grade) < ?', [$minGrade])
            ->pluck('didactic_unit_id');

        // 2. Cursos Regulares (Del semestre que le toca)
        $currentSemesterUnitIds = DidacticUnit::whereHas('module', function ($q) use ($student) {
            $q->where('study_plan_id', $student->study_plan_id);
        })
            ->where('semester', $nextSemester)
            ->pluck('id');

        // 3. Buscar Secciones (TeacherAssignments)
        // [MEJORA] Validamos que existan IDs antes de consultar para optimizar
        $allTargetUnitIds = $currentSemesterUnitIds->merge($failedUnitIds)->unique();

        if ($allTargetUnitIds->isEmpty()) {
            return ['regular' => collect(), 'recovery' => collect()];
        }

        $availableAssignments = TeacherAssignment::with(['didacticUnit', 'shift', 'teacher.user'])
            ->where('academic_period_id', $activePeriodId)
            ->whereIn('didactic_unit_id', $allTargetUnitIds)
            ->where('status', 'active')
            ->get();

        return [
            'regular' => $availableAssignments->whereIn('didactic_unit_id', $currentSemesterUnitIds),
            'recovery' => $availableAssignments->whereIn('didactic_unit_id', $failedUnitIds),
        ];
    }

    /**
     * [NUEVO] Obtiene las series de comprobantes activas para mostrarlas en el select.
     */
    public function getActiveVoucherSeries(): Collection
    {
        return VoucherSeries::where('status', 'active')
            ->orderBy('voucher_type')
            ->get();
    }

    public function validateVoucher(string $series, string $number, int $studentUserId): Voucher
    {
        // Convertimos a mayúsculas por si acaso
        $series = strtoupper(trim($series));

        // A. Buscar Voucher
        $voucher = Voucher::where('series', $series)
            ->where('number', $number)
            ->where('status', 'issued')
            ->first();

        if (!$voucher) {
            throw new \Exception("El comprobante {$series}-{$number} no existe en el sistema o fue anulado.");
        }

        // B. Verificar Cliente
        if ($voucher->client_id !== $studentUserId) {
            // Nota: Podríamos relajar esto si paga un apoderado, pero por seguridad inicial lo mantenemos.
            throw new \Exception("El comprobante pertenece al usuario: {$voucher->client->name} {$voucher->client->lastname}, no al estudiante seleccionado.");
        }

        // C. Verificar Uso
        $isUsed = $voucher->studentPayments()
            ->whereHas('paymentConcept', fn($q) => $q->where('code', 'MAT-REG'))
            ->exists();

        if ($isUsed) {
            throw new \Exception("Este comprobante ya fue utilizado en otra matrícula.");
        }

        return $voucher;
    }
}
