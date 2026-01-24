<?php

namespace Tests\Feature\Academic;

use App\Livewire\Pages\Academic\Careers\CareerManager;
use App\Models\Career;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class CareerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure the permission exists
        Permission::firstOrCreate(['name' => 'gestionar-estructura-academica']);
    }

    public function test_component_can_render()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-estructura-academica');

        Livewire::actingAs($user)
            ->test(CareerManager::class)
            ->assertStatus(200);
    }

    public function test_can_create_career()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-estructura-academica');
        
        $institution = Institution::factory()->create(['status' => 'active']);

        Livewire::actingAs($user)
            ->test(CareerManager::class)
            ->set('institution_id', $institution->id)
            ->set('code', 'TEST-001')
            ->set('name', 'Ingeniería de Software')
            ->set('duration_semesters', 10)
            ->set('status', 'active')
            ->call('save')
            ->assertHasNoErrors()
            ->assertDispatched('swal');

        $this->assertDatabaseHas('careers', [
            'code' => 'TEST-001',
            'name' => 'Ingeniería de Software',
        ]);
    }

    public function test_can_update_career()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-estructura-academica');
        
        $institution = Institution::factory()->create(['status' => 'active']);
        $career = Career::factory()->create([
            'institution_id' => $institution->id,
            'status' => 'active',
        ]);

        Livewire::actingAs($user)
            ->test(CareerManager::class)
            ->call('openEditModal', $career->id)
            ->set('name', 'Updated Career Name')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('careers', [
            'id' => $career->id,
            'name' => 'Updated Career Name',
        ]);
    }

    public function test_can_delete_career()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-estructura-academica');
        
        $career = Career::factory()->create();

        Livewire::actingAs($user)
            ->test(CareerManager::class)
            // Simular confirmación y borrado
            ->call('deleteCareer', $career->id)
            ->assertDispatched('swal'); // Success message

        $this->assertDatabaseMissing('careers', ['id' => $career->id]);
    }

    public function test_validation_works()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-estructura-academica');
        
        Livewire::actingAs($user)
            ->test(CareerManager::class)
            ->call('openCreateModal')
            ->set('name', '') // Empty name
            ->call('save')
            ->assertHasErrors(['name' => 'required']);
    }
}
