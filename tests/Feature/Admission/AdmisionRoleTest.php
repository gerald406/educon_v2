<?php

namespace Tests\Feature\Admission;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Valida que el rol "Admision" tenga acceso exclusivo al módulo de admisión
 * y que NO pueda acceder a ningún otro módulo del sistema.
 */
class AdmisionRoleTest extends TestCase
{
    use RefreshDatabase;

    private User $admisionUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear todos los permisos necesarios
        Permission::firstOrCreate(['name' => 'gestionar-admision']);
        Permission::firstOrCreate(['name' => 'gestionar-institucion']);
        Permission::firstOrCreate(['name' => 'gestionar-estructura-academica']);
        Permission::firstOrCreate(['name' => 'gestionar-docentes']);
        Permission::firstOrCreate(['name' => 'gestionar-estudiantes']);
        Permission::firstOrCreate(['name' => 'gestionar-periodos']);
        Permission::firstOrCreate(['name' => 'registrar-pagos']);
        Permission::firstOrCreate(['name' => 'registrar-notas']);
        Permission::firstOrCreate(['name' => 'registrar-asistencia']);
        Permission::firstOrCreate(['name' => 'subir-silabo']);
        Permission::firstOrCreate(['name' => 'matricularse']);
        Permission::firstOrCreate(['name' => 'ver-mis-asistencias']);
        Permission::firstOrCreate(['name' => 'entregar-actividades']);
        Permission::firstOrCreate(['name' => 'gestionar-biblioteca']);
        Permission::firstOrCreate(['name' => 'gestionar-certificacion']);
        Permission::firstOrCreate(['name' => 'gestionar-roles']);
        Permission::firstOrCreate(['name' => 'gestionar-usuarios']);
        Permission::firstOrCreate(['name' => 'ver-reportes']);

        // Crear el rol Admision con solo su permiso
        $role = Role::firstOrCreate(['name' => 'Admision']);
        $role->syncPermissions(['gestionar-admision']);

        // Crear usuario con rol Admision
        $this->admisionUser = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->admisionUser->assignRole('Admision');
    }

    // =========================================================
    // ACCESO PERMITIDO — módulo Admisión
    // =========================================================

    public function test_admision_user_can_access_admission_dashboard()
    {
        $this->actingAs($this->admisionUser)
            ->get('/admission/dashboard')
            ->assertOk();
    }

    public function test_admision_user_can_access_applicants()
    {
        $this->actingAs($this->admisionUser)
            ->get('/admission/applicants')
            ->assertOk();
    }

    public function test_admision_user_can_access_admission_modalities()
    {
        $this->actingAs($this->admisionUser)
            ->get('/admission/modalities')
            ->assertOk();
    }

    public function test_admision_user_can_access_admission_offerings()
    {
        $this->actingAs($this->admisionUser)
            ->get('/admission/offerings')
            ->assertOk();
    }

    public function test_admision_user_has_gestionar_admision_permission()
    {
        $this->assertTrue($this->admisionUser->can('gestionar-admision'));
    }

    // =========================================================
    // ACCESO DENEGADO — otros módulos
    // =========================================================

    public function test_admision_user_cannot_access_settings()
    {
        $this->actingAs($this->admisionUser)
            ->get('/settings/institution')
            ->assertForbidden();
    }

    public function test_admision_user_cannot_access_academic_structure()
    {
        $this->actingAs($this->admisionUser)
            ->get('/academic/careers')
            ->assertForbidden();
    }

    public function test_admision_user_cannot_access_people_teachers()
    {
        $this->actingAs($this->admisionUser)
            ->get('/people/teachers')
            ->assertForbidden();
    }

    public function test_admision_user_cannot_access_people_students()
    {
        $this->actingAs($this->admisionUser)
            ->get('/people/students')
            ->assertForbidden();
    }

    public function test_admision_user_cannot_access_treasury()
    {
        $this->actingAs($this->admisionUser)
            ->get('/treasury/payments')
            ->assertForbidden();
    }

    public function test_admision_user_cannot_access_evaluation_grades()
    {
        $this->actingAs($this->admisionUser)
            ->get('/evaluation/grades')
            ->assertForbidden();
    }

    public function test_admision_user_cannot_access_teacher_syllabi()
    {
        $this->actingAs($this->admisionUser)
            ->get('/teacher/my-syllabi')
            ->assertForbidden();
    }

    public function test_admision_user_cannot_access_enrollment()
    {
        $this->actingAs($this->admisionUser)
            ->get('/enrollment/process')
            ->assertForbidden();
    }

    public function test_admision_user_cannot_access_library()
    {
        $this->actingAs($this->admisionUser)
            ->get('/services/library-resources')
            ->assertForbidden();
    }

    public function test_admision_user_cannot_access_certification()
    {
        $this->actingAs($this->admisionUser)
            ->get('/certification/certificates')
            ->assertForbidden();
    }

    public function test_admision_user_cannot_access_security_roles()
    {
        $this->actingAs($this->admisionUser)
            ->get('/security/roles')
            ->assertForbidden();
    }

    public function test_admision_user_cannot_access_reports()
    {
        $this->actingAs($this->admisionUser)
            ->get('/reports')
            ->assertForbidden();
    }

    // =========================================================
    // VALIDACIÓN DE PERMISOS
    // =========================================================

    public function test_admision_user_has_only_gestionar_admision_permission()
    {
        $permissions = $this->admisionUser->getAllPermissions()->pluck('name')->toArray();

        $this->assertContains('gestionar-admision', $permissions);

        // No debe tener permisos de otros módulos
        $forbiddenPermissions = [
            'gestionar-institucion',
            'gestionar-estructura-academica',
            'gestionar-docentes',
            'gestionar-estudiantes',
            'registrar-pagos',
            'registrar-notas',
            'registrar-asistencia',
            'subir-silabo',
            'matricularse',
            'gestionar-biblioteca',
            'gestionar-certificacion',
            'gestionar-roles',
            'gestionar-usuarios',
            'ver-reportes',
        ];

        foreach ($forbiddenPermissions as $permission) {
            $this->assertNotContains($permission, $permissions,
                "El rol Admision NO debe tener el permiso: {$permission}"
            );
        }
    }

    public function test_admision_role_exists_with_correct_permissions()
    {
        $role = Role::findByName('Admision');

        $this->assertNotNull($role);
        $this->assertCount(1, $role->permissions);
        $this->assertEquals('gestionar-admision', $role->permissions->first()->name);
    }

    // =========================================================
    // UNAUTHENTICATED — siempre redirige a login
    // =========================================================

    public function test_unauthenticated_user_redirected_from_admission()
    {
        $this->get('/admission/dashboard')->assertRedirect('/login');
    }
}
