<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_assignment_id',

        // --- Campos estructurados principales ---
        'sumilla',                      // ✅ Ya compatible
        'unit_competence',              // ✅ Ya compatible
        'course_capacity',              // ✅ Ya compatible
        'employability_competencies',   // ✅ JSON
        'methodology',                  // ✅ Ya compatible
        'environments',                 // ✅ Ya compatible
        'resources',                    // ✅ Ya compatible
        'evaluation_system',            // ✅ Ya compatible
        'bibliography',                 // ⚠️ Puede ser text o JSON (ver nota abajo)
        'web_sources',                  // ✅ JSON

        // --- Control de estado ---
        'status',                       // draft, submitted, approved, observed, rejected
        'submitted_at',                 // ✅ Nuevo campo agregado
        'observation_notes',            // ✅ Nuevo campo agregado
        'approved_by',                  // ✅ Renombrado desde approved_by_user_id
        'approved_at',                  // ✅ Renombrado desde approval_date

        // --- Campos legacy (mantener por compatibilidad) ---
        'file_url',
        'version',
    ];

    protected $casts = [
        // Fechas
        'approved_at' => 'datetime',    // ✅ Cambiado de 'date' a 'datetime'
        'submitted_at' => 'datetime',   // ✅ Nuevo

        // JSON - Conversión automática a Array
        'employability_competencies' => 'array',
        'bibliography' => 'string',      // ⚠️ Ver nota de compatibilidad abajo
        'web_sources' => 'string',

        // Fechas de ejecución (si existen campos adicionales)
        'execution_date' => 'date',
    ];

    // ============================================
    // RELACIONES
    // ============================================

    /**
     * Relación con la Carga Académica (Asignación de Curso al Docente)
     */
    public function teacherAssignment()
    {
        return $this->belongsTo(TeacherAssignment::class);
    }

    /**
     * Usuario que aprobó el sílabo
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Indicadores de Logro (Apartado 4.2 del sílabo)
     * Ordenados por sort_order
     */
    public function indicators()
    {
        return $this->hasMany(SyllabusIndicator::class)->orderBy('sort_order');
    }

    // ============================================
    // MÉTODOS AUXILIARES (OPCIONAL - RECOMENDADO)
    // ============================================

    /**
     * Verificar si el sílabo está completo para envío
     */
    public function isReadyForSubmission(): bool
    {
        return !empty(trim(strip_tags($this->sumilla))) &&
            !empty(trim(strip_tags($this->methodology))) &&
            !empty(trim(strip_tags($this->evaluation_system))) &&
            $this->indicators()->exists();
    }

    /**
     * Scope para filtrar sílabos por estado
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope para sílabos pendientes de aprobación
     */
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'submitted');
    }
}
