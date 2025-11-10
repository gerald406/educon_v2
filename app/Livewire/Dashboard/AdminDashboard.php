<?php

namespace App\Livewire\Dashboard;

use App\Models\AcademicPeriod;
use App\Models\Career;
use App\Models\Student;
use App\Models\StudentPayment;
use App\Models\Syllabus;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use Illuminate\Support\Collection; // <-- [NUEVO] Importar
use Livewire\Component;

class AdminDashboard extends Component
{
    // --- Propiedades para el Periodo Activo ---
    public ?AcademicPeriod $activePeriod = null;

    // --- Propiedades para las Tarjetas (KPIs) ---
    public $studentCount = 0;
    public $teacherCount = 0;
    public $careerCount = 0;
    public $pendingSyllabi = 0;
    public $assignmentCount = 0;
    public $todayPayments = 0;
    
    // --- Propiedades para las Tablas ---
    public Collection $studentsPerCareer;

    /**
     * Hook 'mount': Carga todas las métricas.
     */
    public function mount()
    {
        $this->activePeriod = AcademicPeriod::where('status', 'active')->first();
        
        // --- Cargar KPIs Globales ---
        
        // 1. Total Estudiantes (Regulares o Irregulares)
        $this->studentCount = Student::whereIn('academic_status', ['regular', 'irregular'])->count();
        
        // 2. Total Docentes Activos
        $this->teacherCount = Teacher::where('status', 'active')->count();
        
        // 3. Total Programas de Estudio Activos
        $this->careerCount = Career::where('status', 'active')->count();

        // 4. Total Pagos de Hoy
        $this->todayPayments = StudentPayment::where('status', 'paid')
                                ->whereDate('payment_date', today())
                                ->sum('final_amount');

        // --- Cargar KPIs del Periodo Activo ---
        if ($this->activePeriod) {
            // 5. Sílabos Pendientes
            $this->pendingSyllabi = Syllabus::where('status', 'pending_approval')
                ->whereHas('teacherAssignment', fn($q) => $q->where('academic_period_id', $this->activePeriod->id))
                ->count();
                
            // 6. Secciones Creadas
            $this->assignmentCount = TeacherAssignment::where('academic_period_id', $this->activePeriod->id)
                                ->count();
        }
        
        // --- Cargar Tablas de Resumen ---
        
        // 7. Estudiantes por Programa de Estudio
        $this->studentsPerCareer = Career::withCount(['students' => function ($query) {
                // Contar solo estudiantes con matrícula activa
                $query->whereIn('academic_status', ['regular', 'irregular']);
            }])
            ->where('status', 'active')
            ->orderBy('students_count', 'desc')
            ->get();
    }
    
    public function render()
    {
        return view('livewire.dashboard.admin-dashboard');
    }
}