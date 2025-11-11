<?php

namespace App\Livewire\Pages\Reports;

use App\Models\AcademicPeriod;
use App\Models\Institution; // Importar Institución
use App\Models\Teacher;
use App\Services\TeacherWorkloadService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage; // <-- [NUEVO] Importar Storage

// [NUEVO] Importar la fachada de Excel y nuestra clase Export
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TeacherWorkloadExport;

#[Layout('layouts.app')]
class ReportManager extends Component
{
    public ?AcademicPeriod $activePeriod = null;
    public ?Institution $institution = null; // <-- [NUEVO] Propiedad para la institución
    public Collection $workloadData;
    
    protected TeacherWorkloadService $workloadService;

    public function __construct()
    {
        $this->workloadService = new TeacherWorkloadService();
    }

    /**
     * Hook 'mount': Carga los datos al iniciar.
     */
    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        $this->institution = Institution::first(); // <-- [NUEVO] Cargar la institución
        $this->loadWorkloadData();
    }

    /**
     * Carga los datos de la carga horaria para la tabla.
     */
    public function loadWorkloadData()
    {
        $this->workloadData = collect();
        if (!$this->activePeriod) {
            return;
        }

        $teachers = Teacher::with('user')->where('status', 'active')->get();
        
        $data = $teachers->map(function ($teacher) {
            $hours = $this->workloadService->calculateWeeklyHours($teacher, $this->activePeriod);
            
            // Determinar estado (Regla #4)
            $statusKey = 'ok';
            $statusText = 'Carga Regular';
            if ($hours > TeacherWorkloadService::MAX_WEEKLY_HOURS) {
                $statusKey = 'overload';
                $statusText = 'Sobrecargado';
            } elseif ($hours < TeacherWorkloadService::MIN_WEEKLY_HOURS && $hours > 0) {
                $statusKey = 'underload';
                $statusText = 'Sub-cargado';
            } elseif ($hours == 0) {
                $statusKey = 'ok';
                $statusText = 'Sin Carga';
            }

            return [
                'name' => $teacher->user->name ?? 'N/A',
                'code' => $teacher->code,
                'hours' => $hours,
                'status_key' => $statusKey,
                'status_text' => $statusText,
            ];
        });

        $this->workloadData = $data->sortBy('name');
    }

    /**
     * Genera y descarga el reporte en PDF.
     * [MÉTODO CORREGIDO]
     */
    public function generateWorkloadPdf()
    {
        if (!$this->activePeriod) return;

        // --- [NUEVA LÓGICA DE LOGO Base64] ---
        $logoData = null;
        if ($this->institution?->logo_url) {
            // Obtener la ruta del disco 'public' (storage/app/public/...)
            $path = Storage::disk('public')->path($this->institution->logo_url);
            
            // Comprobar si el archivo existe físicamente
            if (Storage::disk('public')->exists($this->institution->logo_url)) {
                // Leer el contenido binario del archivo
                $fileContent = file_get_contents($path);
                // Obtener el tipo MIME
                $mime = mime_content_type($path);
                // Codificar en Base64
                $logoData = 'data:' . $mime . ';base64,' . base64_encode($fileContent);
            }
        }
        // --- [FIN LÓGICA DE LOGO] ---

        $data = [
            'workloadData' => $this->workloadData,
            'activePeriod' => $this->activePeriod,
            'institution' => $this->institution,
            'logoData' => $logoData, // <-- [CAMBIO] Pasamos los datos Base64
        ];

        $pdf = Pdf::loadView('reports.teacher-workload-pdf', $data)
                   ->setPaper('a4', 'portrait');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'reporte-carga-horaria-' . $this->activePeriod->code . '.pdf');
    }

    /**
     * [NUEVO MÉTODO]
     * Genera y descarga el reporte en Excel.
     */
    public function generateWorkloadExcel()
    {
        if (!$this->activePeriod) return;

        // Pasamos los datos ya calculados ($this->workloadData) a nuestra clase Export
        return Excel::download(
            new TeacherWorkloadExport($this->workloadData), 
            'reporte-carga-horaria-' . $this->activePeriod->code . '.xlsx'
        );
    }

    public function render()
    {
        return view('livewire.pages.reports.report-manager');
    }
}