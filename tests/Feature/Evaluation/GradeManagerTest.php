<?php

namespace Tests\Feature\Evaluation;

use App\Livewire\Pages\Evaluation\Grades\GradeManager;
use App\Models\AcademicPeriod;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GradeManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'registrar-notas']);
        Role::firstOrCreate(['name' => 'Docente']);
    }

    // Auth is at route level
    public function test_grade_route_requires_authentication()
    {
        $this->get('/evaluation/grades')->assertRedirect('/login');
    }

    public function test_grade_manager_renders_for_teacher()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('registrar-notas');
        $user->assignRole('Docente');
        Teacher::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(GradeManager::class)
            ->assertStatus(200);
    }

    public function test_grade_manager_loads_active_period()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('registrar-notas');
        $user->assignRole('Docente');
        Teacher::factory()->create(['user_id' => $user->id]);

        $period = AcademicPeriod::factory()->create(['status' => 'active']);

        Livewire::actingAs($user)
            ->test(GradeManager::class)
            ->assertSet('activePeriod.id', $period->id);
    }

    public function test_grade_manager_has_assignments_collection()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('registrar-notas');
        $user->assignRole('Docente');
        Teacher::factory()->create(['user_id' => $user->id]);

        $component = Livewire::actingAs($user)
            ->test(GradeManager::class);

        $component->assertStatus(200);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $component->get('assignments'));
    }
}
