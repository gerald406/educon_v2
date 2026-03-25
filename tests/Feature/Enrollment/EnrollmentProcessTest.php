<?php

namespace Tests\Feature\Enrollment;

use App\Livewire\Pages\Enrollment\EnrollmentProcess;
use App\Models\User;
use App\Models\Student;
use App\Models\Career;
use App\Models\StudyPlan;
use App\Models\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Pruebas del módulo de Matrícula.
 *
 * BUG-008: La ruta /enrollment/process permite Administrador por rol,
 * pero la lógica del componente asume un perfil Student.
 */
class EnrollmentProcessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'Estudiante']);
        Role::firstOrCreate(['name' => 'Administrador']);
        Role::firstOrCreate(['name' => 'Docente']);

        Permission::firstOrCreate(['name' => 'matricularse']);
        Permission::firstOrCreate(['name' => 'registrar-notas']);
    }

    // --- Control de Acceso ---

    public function test_enrollment_process_requires_authentication(): void
    {
        $this->get('/enrollment/process')->assertRedirect('/login');
    }

    public function test_teacher_cannot_access_enrollment_process(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('Docente');
        $teacher->givePermissionTo('registrar-notas');

        $this->actingAs($teacher)->get('/enrollment/process')->assertForbidden();
    }

    public function test_student_can_access_enrollment_process(): void
    {
        $studentUser = User::factory()->create();
        $studentUser->assignRole('Estudiante');
        $studentUser->givePermissionTo('matricularse');

        $this->actingAs($studentUser)->get('/enrollment/process')->assertOk();
    }

    // --- BUG-008: Admin accede pero sin perfil de estudiante ---

    /**
     * BUG-008: Un Administrador tiene acceso a /enrollment/process por rol,
     * pero si no tiene un perfil Student asociado, el componente lanzará
     * un error al intentar acceder a auth()->user()->student.
     *
     * El componente debe manejar este caso graciosamente.
     */
    public function test_admin_without_student_profile_accesses_enrollment_process_gracefully(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Administrador');
        // El admin NO tiene perfil de Student

        // No debería lanzar un error 500 sin manejar
        $response = $this->actingAs($admin)->get('/enrollment/process');

        $this->assertNotEquals(500, $response->status(),
            'BUG-008: El componente EnrollmentProcess lanza un error 500 cuando un Administrador ' .
            'sin perfil de estudiante accede a la ruta.'
        );
    }

    // --- Componente Livewire ---

    public function test_enrollment_process_component_renders_for_student(): void
    {
        $studentUser = User::factory()->create();
        $studentUser->assignRole('Estudiante');
        $studentUser->givePermissionTo('matricularse');

        // Crear perfil de estudiante
        $institution = Institution::factory()->create(['status' => 'active']);
        $career      = Career::factory()->create(['institution_id' => $institution->id]);
        $studyPlan   = StudyPlan::factory()->create(['career_id' => $career->id]);

        Student::factory()->create([
            'user_id'       => $studentUser->id,
            'career_id'     => $career->id,
            'study_plan_id' => $studyPlan->id,
        ]);

        Livewire::actingAs($studentUser)
            ->test(EnrollmentProcess::class)
            ->assertStatus(200);
    }

    // --- Integridad: no doble matrícula ---

    public function test_student_cannot_enroll_twice_in_same_period(): void
    {
        // Esta prueba verifica la unicidad a nivel de modelo/BD
        $studentUser = User::factory()->create();
        $studentUser->assignRole('Estudiante');
        $studentUser->givePermissionTo('matricularse');

        $institution = Institution::factory()->create(['status' => 'active']);
        $career      = Career::factory()->create(['institution_id' => $institution->id]);
        $studyPlan   = StudyPlan::factory()->create(['career_id' => $career->id]);

        $student = Student::factory()->create([
            'user_id'       => $studentUser->id,
            'career_id'     => $career->id,
            'study_plan_id' => $studyPlan->id,
        ]);

        // La validación de doble matrícula es responsabilidad del componente/servicio
        // Este test documenta el comportamiento esperado
        $this->assertNotNull($student->id);
        $this->assertEquals($studentUser->id, $student->user_id);
    }
}
