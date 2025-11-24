<?php

namespace App\Livewire\Pages\Admission;

use App\Models\AdmissionModality;
use App\Models\Applicant;
use App\Models\Career;
use App\Models\Shift;
use App\Exports\ApplicantsExport;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.app')]
class AdmissionDashboard extends Component
{
    // --- Métricas del Dashboard ---
    public $totalApplicants = 0;
    public $recentRegistrations = 0;
    public $applicantsByModality = [];
    public $applicantsByProgram = [];

    // --- Filtros para Reportes ---
    // Reporte A
    public $reportA_careerId = '';

    // Reporte B
    public $reportB_careerId = '';
    public $reportB_modalityId = '';
    public $reportB_shiftId = '';

    public function mount()
    {
        $this->loadMetrics();
    }

    public function loadMetrics()
    {
        // 1. Total Postulantes (Mantenemos Eloquent aquí porque es un simple count)
        $this->totalApplicants = Applicant::count();

        // 2. Registros Recientes
        $this->recentRegistrations = Applicant::where('created_at', '>=', now()->subDays(7))->count();

        // 3. Por Modalidad (CORREGIDO con DB::table)
        $this->applicantsByModality = DB::table('applicants')
            ->leftJoin('admission_modalities', 'applicants.admission_modality_id', '=', 'admission_modalities.id')
            ->select(
                DB::raw('COALESCE(admission_modalities.name, "Sin Modalidad") as modality_name'),
                DB::raw('count(*) as total')
            )
            ->groupBy('modality_name') // Agrupamos por el alias (MySQL lo permite) o usa la columna
            ->get();

        // 4. Por Programa (CORREGIDO con DB::table)
        $this->applicantsByProgram = DB::table('applicants')
            ->leftJoin('admission_offerings', 'applicants.admission_offering_id', '=', 'admission_offerings.id')
            ->leftJoin('careers', 'admission_offerings.career_id', '=', 'careers.id')
            ->select(
                DB::raw('COALESCE(careers.name, "Sin Programa Asignado") as career_name'),
                DB::raw('count(*) as total')
            )
            ->groupBy('career_name')
            ->get();
    }

    public function downloadReportA()
    {
        return Excel::download(
            new ApplicantsExport(['career_id' => $this->reportA_careerId]),
            'reporte_postulantes_programa_' . date('Ymd_His') . '.xlsx'
        );
    }

    public function downloadReportB()
    {
        $filters = [
            'career_id' => $this->reportB_careerId,
            'modality_id' => $this->reportB_modalityId,
            'shift_id' => $this->reportB_shiftId,
        ];

        return Excel::download(
            new ApplicantsExport($filters),
            'reporte_postulantes_detallado_' . date('Ymd_His') . '.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.pages.admission.admission-dashboard', [
            'careers' => Career::where('status', 'active')->get(),
            'modalities' => AdmissionModality::where('is_active', true)->get(),
            'shifts' => Shift::all(),
        ]);
    }
}
