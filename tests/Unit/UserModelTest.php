<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'gestionar-institucion']);
        Permission::firstOrCreate(['name' => 'registrar-notas']);
        Permission::firstOrCreate(['name' => 'registrar-asistencia']);
        Permission::firstOrCreate(['name' => 'subir-silabo']);
        Permission::firstOrCreate(['name' => 'matricularse']);
        Permission::firstOrCreate(['name' => 'entregar-actividades']);
        Permission::firstOrCreate(['name' => 'ver-mis-asistencias']);
        Permission::firstOrCreate(['name' => 'gestionar-horarios']);
        Permission::firstOrCreate(['name' => 'aprobar-silabos']);
        Permission::firstOrCreate(['name' => 'gestionar-prerrequisitos']);
    }

    public function test_user_has_admin_access_with_admin_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-institucion');

        $this->assertTrue($user->hasAdminAccess());
    }

    public function test_user_without_permissions_has_no_admin_access()
    {
        $user = User::factory()->create();
        $this->assertFalse($user->hasAdminAccess());
    }

    public function test_teacher_check_excludes_admin_users()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['gestionar-institucion', 'registrar-notas']);

        // Admin takes priority — not considered a plain teacher
        $this->assertFalse($user->isTeacher());
    }

    public function test_teacher_check_for_non_admin_with_teacher_permissions()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('registrar-notas');

        $this->assertTrue($user->isTeacher());
    }

    public function test_student_check_for_non_admin_with_student_permissions()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('matricularse');

        $this->assertTrue($user->isStudent());
    }

    public function test_student_check_excludes_admin_users()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['gestionar-institucion', 'matricularse']);

        $this->assertFalse($user->isStudent());
    }

    public function test_is_coordinator_method_exists()
    {
        $this->assertTrue(method_exists(User::class, 'isCoordinator'));
    }

    public function test_user_relationships_exist()
    {
        $user = User::factory()->create();
        $this->assertNotNull($user->teacher());
        $this->assertNotNull($user->student());
    }
}
