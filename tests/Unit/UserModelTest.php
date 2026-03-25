<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Pruebas unitarias del modelo User.
 *
 * Verifica la lógica de hasAdminAccess(), isTeacher(), isStudent()
 * y detecta BUG-006 (isCoordinator() no definido).
 */
class UserModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear roles y permisos usados por el sistema
        Role::firstOrCreate(['name' => 'Administrador']);
        Role::firstOrCreate(['name' => 'Docente']);
        Role::firstOrCreate(['name' => 'Estudiante']);
        Role::firstOrCreate(['name' => 'Coordinador']);
        Role::firstOrCreate(['name' => 'Secretario Académico']);

        $adminPerms = [
            'gestionar-institucion', 'gestionar-configuracion',
            'gestionar-estructura-academica', 'gestionar-prerrequisitos',
            'gestionar-docentes', 'gestionar-estudiantes',
            'gestionar-periodos', 'gestionar-carga-academica',
            'gestionar-horarios', 'aprobar-silabos',
            'registrar-pagos', 'gestionar-sesiones-caja',
            'gestionar-correlativos', 'registrar-tramites',
            'anular-comprobantes', 'gestionar-certificacion',
            'gestionar-cuadro-meritos', 'gestionar-biblioteca',
            'gestionar-admision', 'gestionar-anuncios',
            'gestionar-reservas-matricula', 'gestionar-reincorporaciones',
            'gestionar-matricula-regular', 'gestionar-roles', 'gestionar-usuarios',
        ];
        foreach ($adminPerms as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $teacherPerms = ['registrar-notas', 'registrar-asistencia', 'subir-silabo'];
        foreach ($teacherPerms as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        $studentPerms = ['matricularse', 'entregar-actividades', 'ver-mis-asistencias'];
        foreach ($studentPerms as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }
    }

    // --- hasAdminAccess() ---

    public function test_admin_user_has_admin_access(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-configuracion');

        $this->assertTrue($admin->hasAdminAccess());
    }

    public function test_student_user_has_no_admin_access(): void
    {
        $student = User::factory()->create();
        $student->givePermissionTo('matricularse');

        $this->assertFalse($student->hasAdminAccess());
    }

    public function test_teacher_user_has_no_admin_access(): void
    {
        $teacher = User::factory()->create();
        $teacher->givePermissionTo('registrar-notas');

        $this->assertFalse($teacher->hasAdminAccess());
    }

    public function test_user_with_no_permissions_has_no_admin_access(): void
    {
        $user = User::factory()->create();
        $this->assertFalse($user->hasAdminAccess());
    }

    // --- isTeacher() ---

    public function test_teacher_user_is_identified_as_teacher(): void
    {
        $teacher = User::factory()->create();
        $teacher->givePermissionTo('registrar-notas');

        $this->assertTrue($teacher->isTeacher());
    }

    public function test_admin_with_teacher_permissions_is_not_identified_as_teacher(): void
    {
        // Un admin que también tiene permisos de docente NO debe ser tratado como docente puro
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-configuracion'); // admin permission
        $admin->givePermissionTo('registrar-notas');         // teacher permission

        $this->assertFalse($admin->isTeacher(),
            'Un usuario con permisos administrativos no debe ser clasificado como docente puro.'
        );
    }

    public function test_student_user_is_not_teacher(): void
    {
        $student = User::factory()->create();
        $student->givePermissionTo('matricularse');

        $this->assertFalse($student->isTeacher());
    }

    // --- isStudent() ---

    public function test_student_user_is_identified_as_student(): void
    {
        $student = User::factory()->create();
        $student->givePermissionTo('matricularse');

        $this->assertTrue($student->isStudent());
    }

    public function test_admin_with_student_permissions_is_not_student(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-configuracion');
        $admin->givePermissionTo('matricularse');

        $this->assertFalse($admin->isStudent(),
            'Un usuario administrativo con permisos de estudiante no debe ser clasificado como estudiante.'
        );
    }

    public function test_teacher_is_not_student(): void
    {
        $teacher = User::factory()->create();
        $teacher->givePermissionTo('registrar-notas');

        $this->assertFalse($teacher->isStudent());
    }

    // --- BUG-006: isCoordinator() no definido ---

    /**
     * BUG-006: El modelo User referencia isCoordinator() en comentarios/docs
     * pero el método no está definido. Llamarlo causaría un BadMethodCallException.
     *
     * Este test verifica que el método existe y funciona correctamente.
     */
    public function test_is_coordinator_method_exists_on_user_model(): void
    {
        $this->assertTrue(
            method_exists(User::class, 'isCoordinator'),
            'BUG-006: El método isCoordinator() no está definido en el modelo User.'
        );
    }

    public function test_coordinator_user_is_identified_correctly(): void
    {
        $this->assertTrue(
            method_exists(User::class, 'isCoordinator'),
            'BUG-006: El método isCoordinator() no está definido en el modelo User.'
        );

        $coordinator = User::factory()->create();
        $coordinator->givePermissionTo('gestionar-horarios'); // permiso de coordinador

        // Si el método existe, debe funcionar sin excepción
        $result = $coordinator->isCoordinator();
        $this->assertIsBool($result);
    }

    // --- Relaciones del modelo ---

    public function test_user_has_teacher_relationship(): void
    {
        $user = User::factory()->create();
        $this->assertNull($user->teacher); // No tiene perfil de docente por defecto
    }

    public function test_user_has_student_relationship(): void
    {
        $user = User::factory()->create();
        $this->assertNull($user->student); // No tiene perfil de estudiante por defecto
    }

    // --- Campos ocultos en serialización ---

    public function test_password_is_hidden_in_serialization(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secret')]);
        $array = $user->toArray();

        $this->assertArrayNotHasKey('password', $array);
        $this->assertArrayNotHasKey('remember_token', $array);
        $this->assertArrayNotHasKey('two_factor_secret', $array);
        $this->assertArrayNotHasKey('two_factor_recovery_codes', $array);
    }

    // --- Mass assignment protection ---

    public function test_user_fillable_does_not_include_sensitive_fields(): void
    {
        $user = new User();
        $fillable = $user->getFillable();

        $this->assertNotContains('id', $fillable);
        $this->assertNotContains('email_verified_at', $fillable);
        $this->assertNotContains('remember_token', $fillable);
        $this->assertNotContains('two_factor_secret', $fillable);
        $this->assertNotContains('two_factor_recovery_codes', $fillable);
    }
}
