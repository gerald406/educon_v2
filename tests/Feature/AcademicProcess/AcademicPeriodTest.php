<?php

namespace Tests\Feature\AcademicProcess;

use App\Livewire\Pages\AcademicProcess\AcademicPeriods\AcademicPeriodManager;
use App\Models\AcademicPeriod;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AcademicPeriodTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'gestionar-periodos']);
    }

    // Auth is enforced at route level, not component level
    public function test_route_requires_authentication()
    {
        $response = $this->get('/academic-process/academic-periods');
        $response->assertRedirect('/login');
    }

    public function test_component_renders_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-periodos');

        Livewire::actingAs($user)
            ->test(AcademicPeriodManager::class)
            ->assertStatus(200);
    }

    public function test_save_action_requires_permission()
    {
        $user = User::factory()->create(); // No permission

        Livewire::actingAs($user)
            ->test(AcademicPeriodManager::class)
            ->call('save')
            ->assertForbidden();
    }

    public function test_academic_period_factory_creates_unique_codes()
    {
        $period1 = AcademicPeriod::factory()->create();
        $period2 = AcademicPeriod::factory()->create();

        $this->assertNotEquals($period1->code, $period2->code);
    }

    public function test_academic_period_has_correct_relationships()
    {
        $period = AcademicPeriod::factory()->create();

        $this->assertNotNull($period->institution_id);
        $this->assertNotNull($period->academic_year_id);
    }
}
