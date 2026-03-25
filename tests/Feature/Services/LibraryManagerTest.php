<?php

namespace Tests\Feature\Services;

use App\Livewire\Pages\Services\Library\LibraryResourceManager;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class LibraryManagerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'gestionar-biblioteca']);
    }

    // Auth enforced at route level
    public function test_library_route_requires_authentication()
    {
        $this->get('/services/library-resources')->assertRedirect('/login');
    }

    public function test_library_manager_renders_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-biblioteca');
        Institution::factory()->create();

        Livewire::actingAs($user)
            ->test(LibraryResourceManager::class)
            ->assertStatus(200);
    }

    // NOTE: LibraryResourceManager relies only on route-level middleware for auth.
    // BUG-013: Component actions (save, deleteResource) lack $this->authorize() guards,
    // meaning any authenticated user can call them via Livewire directly.
    public function test_library_route_requires_permission()
    {
        $user = User::factory()->create(); // No gestionar-biblioteca permission

        $this->actingAs($user)
            ->get('/services/library-resources')
            ->assertForbidden();
    }

    // BUG-009: Verify no null-pointer when no institution exists
    public function test_library_manager_survives_empty_institution()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-biblioteca');

        // No institution created — institution_id should be null, not throw
        $component = Livewire::actingAs($user)
            ->test(LibraryResourceManager::class);

        $component->assertStatus(200);
        $this->assertNull($component->get('institution_id'));
    }
}
