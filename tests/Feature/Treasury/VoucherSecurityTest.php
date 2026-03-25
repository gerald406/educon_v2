<?php

namespace Tests\Feature\Treasury;

use App\Livewire\Pages\Treasury\CashSessionManager;
use App\Livewire\Pages\Treasury\VoucherSeriesManager;
use App\Models\CashSession;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class VoucherSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'registrar-pagos']);
        Permission::firstOrCreate(['name' => 'gestionar-sesiones-caja']);
        Permission::firstOrCreate(['name' => 'gestionar-correlativos']);
        Permission::firstOrCreate(['name' => 'anular-comprobantes']);
    }

    // Auth is enforced at route level (treasury middleware group)
    public function test_treasury_route_requires_authentication()
    {
        $this->get('/treasury/payments')->assertRedirect('/login');
    }

    public function test_cash_session_route_requires_authentication()
    {
        $this->get('/treasury/cash-sessions')->assertRedirect('/login');
    }

    public function test_voucher_download_route_requires_authentication()
    {
        $voucher = Voucher::factory()->create();
        $this->get("/treasury/voucher/{$voucher->id}/download")->assertRedirect('/login');
    }

    public function test_voucher_download_requires_permission()
    {
        $user = User::factory()->create(); // No registrar-pagos permission
        $voucher = Voucher::factory()->create();

        $this->actingAs($user)
            ->get("/treasury/voucher/{$voucher->id}/download")
            ->assertForbidden();
    }

    public function test_cash_session_manager_renders_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['registrar-pagos', 'gestionar-sesiones-caja']);

        Livewire::actingAs($user)
            ->test(CashSessionManager::class)
            ->assertStatus(200);
    }

    public function test_voucher_series_manager_renders_with_permission()
    {
        $user = User::factory()->create();
        $user->givePermissionTo(['registrar-pagos', 'gestionar-correlativos']);

        Livewire::actingAs($user)
            ->test(VoucherSeriesManager::class)
            ->assertStatus(200);
    }

    public function test_voucher_has_required_fields()
    {
        $voucher = Voucher::factory()->create([
            'voucher_type' => 'boleta',
            'series' => 'B001',
            'number' => 1,
            'status' => 'issued',
        ]);

        $this->assertDatabaseHas('vouchers', [
            'id' => $voucher->id,
            'series' => 'B001',
            'number' => 1,
        ]);
    }

    // BUG-001: verify no public (unauthenticated) voucher download route exists
    public function test_all_voucher_download_routes_require_auth()
    {
        $routes = collect(\Route::getRoutes())->filter(function ($route) {
            return str_contains($route->uri(), 'voucher') && str_contains($route->uri(), 'download');
        });

        $this->assertNotEmpty($routes, 'Voucher download route must exist');

        foreach ($routes as $route) {
            $middlewares = $route->gatherMiddleware();
            $this->assertContains('auth', $middlewares,
                "Voucher download route '{$route->uri()}' must have auth middleware"
            );
        }
    }
}
