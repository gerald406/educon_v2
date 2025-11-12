<?php

namespace App\Livewire\Pages\Certification;

use App\Models\AcademicPeriod;
use App\Models\AcademicRecord;
use App\Models\MeritRanking;
use App\Models\Student;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class MeritRankingManager extends Component
{
    use WithPagination;

    // --- PROPIEDADES DE FILTRO ---
    public $selectedPeriodId = '';
    public Collection $academicPeriods;

    // --- ESTADO ---
    public $search = '';

    public function mount()
    {
        // Cargar periodos cerrados o activos (donde ya hay notas)
        $this->academicPeriods = AcademicPeriod::whereIn('status', ['active', 'closed'])
                                ->orderBy('start_date', 'desc')
                                ->pluck('name', 'id');
        
        // Seleccionar el primero por defecto
        $this->selectedPeriodId = $this->academicPeriods->keys()->first() ?? '';
    }

    /**
     * Acción principal: Generar el Cuadro de Méritos para el periodo seleccionado.
     */
    public function generateRanking()
    {
        $this->validate(['selectedPeriodId' => 'required']);

        $periodId = $this->selectedPeriodId;
        
        // (En un futuro, esto debería llamar a un Stored Procedure o un Service)
        // Lógica de cálculo simplificada:
        try {
            DB::transaction(function () use ($periodId) {
                // 1. Borrar ranking anterior para este periodo
                MeritRanking::where('academic_period_id', $periodId)->delete();

                // 2. Calcular el promedio ponderado y créditos del periodo
                $studentData = AcademicRecord::where('academic_period_id', $periodId)
                    ->join('didactic_units', 'academic_records.didactic_unit_id', '=', 'didactic_units.id')
                    ->where('academic_records.course_status', 'approved') // Solo cursos aprobados
                    ->groupBy('academic_records.student_id')
                    ->select(
                        'academic_records.student_id',
                        DB::raw('SUM(academic_records.final_grade * didactic_units.credits) / SUM(didactic_units.credits) as weighted_average'),
                        DB::raw('SUM(didactic_units.credits) as period_credits')
                    )
                    ->orderBy('weighted_average', 'desc')
                    ->get();
                
                // 3. Insertar los nuevos rankings
                $position = 1;
                foreach ($studentData as $data) {
                    MeritRanking::create([
                        'student_id' => $data->student_id,
                        'academic_period_id' => $periodId,
                        'weighted_average' => $data->weighted_average,
                        'period_credits' => $data->period_credits,
                        'general_position' => $position++,
                        'calculation_date' => now(),
                    ]);
                }
            });

            $this->dispatch('swal', ['icon' => 'success', 'title' => '¡Éxito!', 'text' => 'Cuadro de méritos generado correctamente.']);

        } catch (\Exception $e) {
            $this->dispatch('swal', ['icon' => 'error', 'title' => 'Error', 'text' => 'No se pudo generar el ranking: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        // Cargar el ranking existente para el periodo seleccionado
        $query = MeritRanking::with(['student.user', 'student.career'])
                    ->where('academic_period_id', $this->selectedPeriodId);
        
        if ($this->search) {
            $query->whereHas('student.user', fn($q) => $q->where('name', 'like', '%'.$this->search.'%'))
                ->orWhereHas('student', fn($q) => $q->where('code', 'like', '%'.$this->search.'%'));
        }
        
        $rankings = $query->orderBy('general_position', 'asc')->paginate(20);

        return view('livewire.pages.certification.merit-ranking-manager', [
            'rankings' => $rankings,
        ]);
    }
}