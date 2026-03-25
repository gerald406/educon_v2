<?php

namespace Tests\Feature\People;

use App\Livewire\Pages\People\Students\StudentManager;
use App\Livewire\Pages\People\Teachers\TeacherManager;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Career;
use App\Models\Institution;
use App\Models\StudyPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Pruebas del módulo de Personas (Docentes y Estudiantes).
 */
class PeopleManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'gestionar-docentes']);
        Permission::firstOrCreate(['name' => 'gestionar-estudiantes']);
        Permission::firstOrCreate(['name' => 'matricularse']);
        // Los componentes StudentManager/TeacherManager filtran por rol
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Estudiante', 'guard_name' => 'web']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Docente', 'guard_name' => 'web']);
    }

    // --- StudentManager ---

    public function test_student_manager_requires_authentication(): void
    {
        $this->get('/people/students')->assertRedirect('/login');
    }

    public function test_student_manager_requires_correct_permission(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/people/students')->assertForbidden();
    }

    public function test_student_manager_renders_with_permission(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-estudiantes');

        $this->actingAs($admin)->get('/people/students')->assertOk();
    }

    public function test_student_manager_component_renders(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-estudiantes');

        Livewire::actingAs($admin)
            ->test(StudentManager::class)
            ->assertStatus(200);
    }

    // --- TeacherManager ---

    public function test_teacher_manager_requires_authentication(): void
    {
        $this->get('/people/teachers')->assertRedirect('/login');
    }

    public function test_teacher_manager_requires_correct_permission(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/people/teachers')->assertForbidden();
    }

    public function test_teacher_manager_renders_with_permission(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-docentes');

        $this->actingAs($admin)->get('/people/teachers')->assertOk();
    }

    public function test_teacher_manager_component_renders(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-docentes');

        Livewire::actingAs($admin)
            ->test(TeacherManager::class)
            ->assertStatus(200);
    }

    // --- Enrollment form download ---

    public function test_enrollment_form_download_requires_authentication(): void
    {
        $this->get('/people/students/1/enrollment-form')->assertRedirect('/login');
    }

    public function test_enrollment_form_download_requires_gestionar_estudiantes(): void
    {
        // Crear un estudiante real para que el model binding no retorne 404
        Permission::firstOrCreate(['name' => 'matricularse']);
        $student = Student::factory()->create();

        $userWithoutPermission = User::factory()->create();
        // Sin permiso 'gestionar-estudiantes'

        $response = $this->actingAs($userWithoutPermission)
            ->get("/people/students/{$student->id}/enrollment-form");
        $response->assertForbidden();
    }

    public function test_enrollment_form_returns_404_for_nonexistent_student(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-estudiantes');

        $response = $this->actingAs($admin)->get('/people/students/99999/enrollment-form');
        $response->assertNotFound();
    }

    // --- Integridad de datos del estudiante ---

    public function test_student_model_soft_deletes(): void
    {
        $studentUser = User::factory()->create();
        $institution = Institution::factory()->create(['status' => 'active']);
        $career      = Career::factory()->create(['institution_id' => $institution->id]);
        $studyPlan   = StudyPlan::factory()->create(['career_id' => $career->id]);

        $student = Student::factory()->create([
            'user_id'       => $studentUser->id,
            'career_id'     => $career->id,
            'study_plan_id' => $studyPlan->id,
        ]);

        $student->delete();

        // Con SoftDeletes, el registro sigue en BD
        $this->assertSoftDeleted('students', ['id' => $student->id]);

        // No aparece en consultas normales
        $this->assertNull(Student::find($student->id));
    }

    public function test_teacher_model_soft_deletes(): void
    {
        $teacherUser = User::factory()->create();
        $institution = Institution::factory()->create(['status' => 'active']);

        $teacher = Teacher::factory()->create([
            'user_id'        => $teacherUser->id,
            'institution_id' => $institution->id,
        ]);

        $teacher->delete();

        $this->assertSoftDeleted('teachers', ['id' => $teacher->id]);
        $this->assertNull(Teacher::find($teacher->id));
    }

    // --- Mass assignment protection ---

    public function test_student_fillable_does_not_expose_dangerous_fields(): void
    {
        $student = new Student();
        $fillable = $student->getFillable();

        $this->assertNotContains('id', $fillable);
        $this->assertNotContains('deleted_at', $fillable);
        $this->assertContains('user_id', $fillable);
        $this->assertContains('career_id', $fillable);
    }
}
