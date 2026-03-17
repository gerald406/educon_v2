<?php

namespace App\Livewire\Pages\Admission;

use App\Models\AdmissionModality;
use App\Models\AdmissionOffering;
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
    // --- KPIs ---
    public int $totalApplicants      = 0;
    public int $recentRegistrations  = 0;
    public int $approvedApplicants   = 0;
    public int $pendingApplicants    = 0;

    // --- Datos para gráficos ---
    public array $applicantsByModality  = [];
    public array $applicantsByProgram   = [];
    public array $applicantsByShift     = [];
    public array $applicantsByStatus    = [];
    public array $applicantsByDistrict  = [];

    // --- Tabla resumen por oferta ---
    public array $offeringSummary = [];

    // --- Filtros para Reportes ---
    public $reportA_careerId   = '';
    public $reportB_careerId   = '';
    public $reportB_modalityId = '';
    public $reportB_shiftId    = '';

    public function mount()
    {
        $this->loadMetrics();
    }

    public function loadMetrics(): void
    {
        // ============================================
        // KPIs
        // ============================================

        $this->totalApplicants = Applicant::count();

        $this->recentRegistrations = Applicant::where('created_at', '>=', now()->subDays(7))
            ->count();

        $this->approvedApplicants = Applicant::where('application_status', 'aprobado')
            ->count();

        $this->pendingApplicants = Applicant::whereIn('application_status', ['registrado', 'evaluado'])
            ->count();

        // ============================================
        // Gráfico 1 — Por Modalidad
        // ============================================
        $modalityData = DB::table('applicants')
            ->leftJoin('admission_modalities', 'applicants.admission_modality_id', '=', 'admission_modalities.id')
            ->select(
            DB::raw('COALESCE(admission_modalities.name, "Sin Modalidad") as label'),
            DB::raw('COUNT(*) as total')
            )
            ->whereNull('applicants.deleted_at')
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $this->applicantsByModality = [
            'labels' => $modalityData->pluck('label')->toArray(),
            'data'   => $modalityData->pluck('total')->toArray(),
        ];

        // ============================================
        // Gráfico 2 — Por Programa de Estudios
        // ============================================
        $programData = DB::table('applicants')
            ->leftJoin('admission_offerings', 'applicants.admission_offering_id', '=', 'admission_offerings.id')
            ->leftJoin('careers', 'admission_offerings.career_id', '=', 'careers.id')
            ->select(
            DB::raw('COALESCE(careers.name, "Sin Programa") as label'),
            DB::raw('COUNT(*) as total')
            )
            ->whereNull('applicants.deleted_at')
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $this->applicantsByProgram = [
            'labels' => $programData->pluck('label')->toArray(),
            'data'   => $programData->pluck('total')->toArray(),
        ];

        // ============================================
        // Gráfico 3 — Por Turno
        // ============================================
        $shiftData = DB::table('applicants')
            ->leftJoin('admission_offerings', 'applicants.admission_offering_id', '=', 'admission_offerings.id')
            ->leftJoin('shifts', 'admission_offerings.shift_id', '=', 'shifts.id')
            ->select(
                DB::raw('COALESCE(shifts.name, "Sin Turno") as label'),
                DB::raw('COUNT(*) as total')
            )
            ->whereNull('applicants.deleted_at')
            ->groupBy('label')
            ->orderByDesc('total')
            ->get();

        $this->applicantsByShift = [
            'labels' => $shiftData->pluck('label')->toArray(),
            'data'   => $shiftData->pluck('total')->toArray(),
        ];

        // ============================================
        // Gráfico 4 — Por Estado del proceso
        // ============================================
        $statusLabels = [
            'registrado' => 'Registrado',
            'evaluado'   => 'Evaluado',
            'aprobado'   => 'Aprobado',
            'sin_vacante' => 'Sin Vacante',
            'cancelado'  => 'Cancelado',
        ];

        $statusData = DB::table('applicants')
            ->select(
                'application_status as status',
                DB::raw('COUNT(*) as total')
            )
            ->whereNull('deleted_at')
            ->groupBy('application_status')
            ->get();

        $this->applicantsByStatus = [
            'labels' => $statusData->map(fn($r) => $statusLabels[$r->status] ?? $r->status)->toArray(),
            'data'   => $statusData->pluck('total')->toArray(),
            'colors' => $statusData->map(function ($r) {
                return match ($r->status) {
                    'registrado'  => 'rgba(59, 130, 246, 0.7)',
                    'evaluado'    => 'rgba(245, 158, 11, 0.7)',
                    'aprobado'    => 'rgba(16, 185, 129, 0.7)',
                    'sin_vacante' => 'rgba(239, 68, 68, 0.7)',
                    'cancelado'   => 'rgba(107, 114, 128, 0.7)',
                    default       => 'rgba(156, 163, 175, 0.7)',
                };
            })->toArray(),
        ];

        // ============================================
        // Gráfico 5 — Por Distrito de Nacimiento
        // ============================================
        $districtData = DB::table('applicants')
            ->leftJoin('locations', 'applicants.ubigeo_birth_id', '=', 'locations.iddist')
            ->select(
                DB::raw('COALESCE(locations.nombdist, "Sin Datos") as label'),
                DB::raw('COUNT(*) as total')
            )
            ->whereNull('applicants.deleted_at')
            ->groupBy('label')
            ->orderByDesc('total')
            ->limit(10) // Top 10 distritos
            ->get();

        $this->applicantsByDistrict = [
            'labels' => $districtData->pluck('label')->toArray(),
            'data'   => $districtData->pluck('total')->toArray(),
        ];

        // ============================================
        // Tabla resumen por Oferta (Programa + Turno)
        // ============================================
        $this->offeringSummary = DB::table('applicants')
            ->leftJoin('admission_offerings', 'applicants.admission_offering_id', '=', 'admission_offerings.id')
            ->leftJoin('careers', 'admission_offerings.career_id', '=', 'careers.id')
            ->leftJoin('shifts', 'admission_offerings.shift_id', '=', 'shifts.id')
            ->select(
                DB::raw('COALESCE(careers.name, "Sin Programa") as career'),
                DB::raw('COALESCE(shifts.name, "Sin Turno") as shift'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN applicants.application_status = "aprobado" THEN 1 ELSE 0 END) as approved'),
                DB::raw('SUM(CASE WHEN applicants.application_status = "registrado" THEN 1 ELSE 0 END) as registered'),
                DB::raw('SUM(CASE WHEN applicants.application_status = "evaluado" THEN 1 ELSE 0 END) as evaluated'),
                DB::raw('SUM(CASE WHEN applicants.application_status = "sin_vacante" THEN 1 ELSE 0 END) as no_vacancy'),
                DB::raw('SUM(CASE WHEN applicants.application_status = "cancelado" THEN 1 ELSE 0 END) as cancelled'),
                DB::raw('COALESCE(admission_offerings.vacancies, 0) as vacancies')
            )
            ->whereNull('applicants.deleted_at')
            ->groupBy('careers.name', 'shifts.name', 'admission_offerings.vacancies')
            ->orderBy('careers.name')
            ->get()
            ->toArray();
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
        return Excel::download(
            new ApplicantsExport([
                'career_id'   => $this->reportB_careerId,
                'modality_id' => $this->reportB_modalityId,
                'shift_id'    => $this->reportB_shiftId,
            ]),
            'reporte_postulantes_detallado_' . date('Ymd_His') . '.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.pages.admission.admission-dashboard', [
            'careers'    => Career::where('status', 'active')->orderBy('name')->get(),
            'modalities' => AdmissionModality::where('is_active', true)->get(),
            'shifts'     => Shift::all(),
        ]);
    }
}
