<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RouteProtectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'registrar-pagos']);
        Permission::firstOrCreate(['name' => 'gestionar-roles']);
        Permission::firstOrCreate(['name' => 'gestionar-usuarios']);
        Permission::firstOrCreate(['name' => 'gestionar-admision']);
        Permission::firstOrCreate(['name' => 'gestionar-anuncios']);
    }

    public function test_treasury_routes_require_authentication()
    {
        $response = $this->get('/treasury/payments');
        $response->assertRedirect('/login');
    }

    public function test_security_routes_require_authentication()
    {
        $response = $this->get('/security/roles');
        $response->assertRedirect('/login');
    }

    public function test_voucher_download_requires_authentication()
    {
        // Create a real voucher so route binding resolves
        $voucher = Voucher::factory()->create();

        $response = $this->get("/treasury/voucher/{$voucher->id}/download");
        $response->assertRedirect('/login');
    }

    public function test_voucher_download_requires_permission()
    {
        $user = User::factory()->create();
        $voucher = Voucher::factory()->create();

        $response = $this->actingAs($user)->get("/treasury/voucher/{$voucher->id}/download");
        $response->assertForbidden();
    }

    public function test_authenticated_user_without_permission_cannot_access_security_routes()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/security/roles');
        $response->assertForbidden();
    }

    public function test_admission_routes_require_authentication()
    {
        $response = $this->get('/admission/dashboard');
        $response->assertRedirect('/login');
    }

    // BUG-001: Verify there is no public voucher download route
    public function test_no_public_voucher_route_exists()
    {
        $routes = collect(\Route::getRoutes())->filter(function ($route) {
            return str_contains($route->uri(), 'voucher') && str_contains($route->uri(), 'download');
        });

        foreach ($routes as $route) {
            $middlewares = $route->gatherMiddleware();
            $this->assertContains('auth', $middlewares,
                "Voucher download route '{$route->uri()}' must have auth middleware"
            );
        }
    }
}
