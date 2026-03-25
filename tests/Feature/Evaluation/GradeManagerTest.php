<?php

namespace Tests\Feature\Evaluation;

use App\Livewire\Pages\Evaluation\Grades\GradeManager;
use App\Models\User;
use App\Models\Grade;
use App\Models\Registration;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\TeacherAssignment;
use App\Models\AcademicPeriod;
use App\Models\EvaluationType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Pruebas del módulo de Evaluación (Notas).
 *
 * BUG-003: Un docente podría registrar notas en cursos que no le pertenecen.
 */
class GradeManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Docente']);
        Role::firstOrCreate(['name' => 'Administrador']);
        Role::firstOrCreate(['name' => 'Coordinador']);
        Role::firstOrCreate(['name' => 'Estudiante']);

        Permission::firstOrCreate(['name' => 'registrar-notas']);
        Permission::firstOrCreate(['name' => 'registrar-asistencia']);
        Permission::firstOrCreate(['name' => 'subir-silabo']);
        Permission::firstOrCreate(['name' => 'matricularse']);
    }

    // --- Control de Acceso ---

    public function test_grade_manager_requires_authentication(): void
    {
        $this->get('/evaluation/grades')->assertRedirect('/login');
    }

    public function test_student_cannot_access_grade_manager(): void
    {
        $student = User::factory()->create();
        $student->assignRole('Estudiante');
        $student->givePermissionTo('matricularse');

        $this->actingAs($student)->get('/evaluation/grades')->assertForbidden();
    }

    public function test_teacher_can_access_grade_manager(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('Docente');
        $teacher->givePermissionTo('registrar-notas');

        $this->actingAs($teacher)->get('/evaluation/grades')->assertOk();
    }

    public function test_admin_can_access_grade_manager(): void
    {
        Role::firstOrCreate(['name' => 'Administrador']);
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');
        $admin->givePermissionTo('registrar-notas');

        $this->actingAs($admin)->get('/evaluation/grades')->assertOk();
    }

    // --- Renderizado del componente ---

    public function test_grade_manager_component_renders(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('Docente');
        $teacher->givePermissionTo('registrar-notas');

        Livewire::actingAs($teacher)
            ->test(GradeManager::class)
            ->assertStatus(200);
    }

    // --- BUG-003: Validación de propiedad de cursos ---

    /**
     * BUG-003: Un docente solo debe poder ver/registrar notas en los cursos
     * que tiene asignados. El GradeManager debe filtrar por el docente autenticado.
     *
     * Este test verifica que la lista de asignaciones disponibles en el componente
     * corresponde solo al docente autenticado.
     */
    public function test_grade_manager_only_shows_own_teacher_assignments(): void
    {
        $teacherUser1 = User::factory()->create();
        $teacherUser1->assignRole('Docente');
        $teacherUser1->givePermissionTo('registrar-notas');

        $teacherUser2 = User::factory()->create();
        $teacherUser2->assignRole('Docente');
        $teacherUser2->givePermissionTo('registrar-notas');

        // Crear perfiles de docente
        $teacher1 = Teacher::factory()->create(['user_id' => $teacherUser1->id]);
        $teacher2 = Teacher::factory()->create(['user_id' => $teacherUser2->id]);

        // Crear una asignación para teacher2
        $assignment = TeacherAssignment::factory()->create([
            'teacher_id' => $teacher2->id,
        ]);

        // teacher1 accede al componente — NO debe ver la asignación de teacher2
        $component = Livewire::actingAs($teacherUser1)
            ->test(GradeManager::class);

        $component->assertStatus(200);

        // 'assignments' es una propiedad Livewire (Collection), no viewData.
        // El componente filtra por teacher_id del usuario autenticado (loadAssignments).
        // teacher1 no tiene assignments, teacher2 sí → las claves del Collection de teacher1 deben estar vacías
        $assignmentsKeys = array_keys($component->get('assignments')->toArray());

        $this->assertNotContains(
            $assignment->id,
            $assignmentsKeys,
            'BUG-003: El docente puede ver asignaciones de otro docente en GradeManager.'
        );
    }

    // --- Validación de datos de nota ---

    /**
     * Verifica que la tabla grades acepta valores y que el modelo tiene los campos correctos.
     * NOTA: La validación de rango (0-20) es responsabilidad de la capa de aplicación
     * (Livewire/Controllers), no de la BD. Este test documenta el comportamiento.
     */
    public function test_grade_model_accepts_valid_grade_values(): void
    {
        $teacherUser = User::factory()->create();
        $teacherUser->assignRole('Docente');
        $teacherUser->givePermissionTo('registrar-notas');

        $evaluationType = EvaluationType::factory()->create();
        $registration   = Registration::factory()->create();

        $grade = Grade::create([
            'registration_id'       => $registration->id,
            'evaluation_type_id'    => $evaluationType->id,
            'registered_by_user_id' => $teacherUser->id,
            'grade'                 => 15.50, // Nota válida
            'evaluation_date'       => now(),
        ]);

        $this->assertDatabaseHas('grades', [
            'id'    => $grade->id,
            'grade' => 15.50,
        ]);
    }

    /**
     * Documenta que NO existe constraint de BD para valores de nota > 20.
     * La validación 0-20 debe estar en la lógica del componente GradeManager.
     * Este es un riesgo de integridad de datos si se accede directamente al modelo.
     */
    public function test_grade_model_has_no_db_constraint_for_range(): void
    {
        $teacherUser = User::factory()->create();
        $teacherUser->assignRole('Docente');
        $teacherUser->givePermissionTo('registrar-notas');

        $evaluationType = EvaluationType::factory()->create();
        $registration   = Registration::factory()->create();

        // Sin constraint de BD, un valor inválido puede insertarse directamente
        $grade = Grade::create([
            'registration_id'       => $registration->id,
            'evaluation_type_id'    => $evaluationType->id,
            'registered_by_user_id' => $teacherUser->id,
            'grade'                 => 25.00, // VALOR INVÁLIDO - debe ser rechazado por la app
            'evaluation_date'       => now(),
        ]);

        // El modelo acepta el valor - RIESGO: La validación solo existe en la UI
        // TODO: Considerar agregar CHECK constraint en la migración para mayor seguridad
        $this->assertNotNull($grade->id,
            'RIESGO: No existe constraint de BD para grade <= 20. ' .
            'La validación solo existe a nivel de aplicación.'
        );
    }
}
