<?php

namespace Tests\Feature\People;

use App\Livewire\Pages\People\Teachers\TeacherManager;
use App\Livewire\Pages\People\Students\StudentManager;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PeopleManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'gestionar-docentes']);
        Permission::firstOrCreate(['name' => 'gestionar-estudiantes']);
        Role::firstOrCreate(['name' => 'Docente']);
        Role::firstOrCreate(['name' => 'Estudiante']);
    }

    // Auth is at route level — test routes, not component mount
    public function test_teacher_route_requires_authentication()
    {
        $this->get('/people/teachers')->assertRedirect('/login');
    }

    public function test_student_route_requires_authentication()
    {
        $this->get('/people/students')->assertRedirect('/login');
    }

    public function test_teacher_manager_renders_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-docentes');

        Livewire::actingAs($user)
            ->test(TeacherManager::class)
            ->assertStatus(200);
    }

    public function test_teacher_create_action_requires_permission()
    {
        $user = User::factory()->create(); // No permission

        Livewire::actingAs($user)
            ->test(TeacherManager::class)
            ->call('save')
            ->assertForbidden();
    }

    public function test_student_manager_renders_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-estudiantes');

        Livewire::actingAs($user)
            ->test(StudentManager::class)
            ->assertStatus(200);
    }

    public function test_student_create_action_requires_permission()
    {
        $user = User::factory()->create(); // No permission

        Livewire::actingAs($user)
            ->test(StudentManager::class)
            ->call('save')
            ->assertForbidden();
    }

    public function test_teacher_can_be_created_via_factory()
    {
        $teacher = Teacher::factory()->create();

        $this->assertDatabaseHas('teachers', ['id' => $teacher->id]);
        $this->assertNotNull($teacher->user);
    }
}
