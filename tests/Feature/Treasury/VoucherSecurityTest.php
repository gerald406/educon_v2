<?php

namespace Tests\Feature\Treasury;

use App\Models\User;
use App\Models\Voucher;
use App\Models\CashSession;
use App\Models\CreditNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Pruebas de seguridad del módulo Tesorería.
 *
 * BUG-001: Ruta /voucher/{id}/download duplicada sin autenticación.
 * BUG-002: VoucherController no verifica propiedad del comprobante.
 */
class VoucherSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'registrar-pagos']);
        Permission::firstOrCreate(['name' => 'gestionar-sesiones-caja']);
        Permission::firstOrCreate(['name' => 'anular-comprobantes']);
    }

    /**
     * BUG-001 (CORREGIDO): La ruta pública /voucher/{id}/download fue eliminada.
     *
     * Verifica que la ruta pública ya no existe (404) y que la protegida
     * en /treasury/voucher/{id}/download sí requiere auth (redirect a login).
     */
    public function test_public_voucher_download_route_is_not_accessible_without_auth(): void
    {
        // La ruta pública fue eliminada: debe retornar 404
        $this->get('/voucher/1/download')->assertNotFound();

        // La ruta treasury protegida redirige a login si no hay sesión
        $this->get('/treasury/voucher/1/download')->assertRedirect('/login');
    }

    /**
     * BUG-002: El usuario autenticado sin permiso no debe descargar vouchers.
     */
    public function test_user_without_treasury_permission_cannot_download_voucher(): void
    {
        $owner = User::factory()->create();
        $userWithoutPermission = User::factory()->create();

        $voucher = Voucher::factory()->create([
            'issuer_id' => $owner->id,
            'client_id' => $owner->id,
        ]);

        // Usuario sin permiso 'registrar-pagos' accede a la ruta treasury protegida
        $response = $this->actingAs($userWithoutPermission)
            ->get("/treasury/voucher/{$voucher->id}/download");

        $response->assertForbidden();
    }

    /**
     * Un usuario con permiso 'registrar-pagos' puede descargar cualquier voucher.
     * Esto valida que el permiso es el control correcto para este recurso.
     */
    public function test_treasury_user_with_permission_can_access_voucher_download_route(): void
    {
        $cashier = User::factory()->create();
        $cashier->givePermissionTo('registrar-pagos');

        $client = User::factory()->create();
        $voucher = Voucher::factory()->create([
            'issuer_id' => $cashier->id,
            'client_id' => $client->id,
        ]);

        // Solo verificamos que la ruta responde (el PDF real requiere DomPDF y datos completos)
        // Esperamos 200 o 500 (error de vista), NO 401 ni 403
        $response = $this->actingAs($cashier)
            ->get("/treasury/voucher/{$voucher->id}/download");

        $this->assertNotEquals(401, $response->status(),
            'Un usuario con permiso válido no debe recibir 401 Unauthorized.'
        );
        $this->assertNotEquals(403, $response->status(),
            'Un usuario con permiso válido no debe recibir 403 Forbidden.'
        );
    }

    /**
     * La nota de crédito tampoco debe ser accesible sin autenticación.
     */
    public function test_credit_note_download_requires_authentication(): void
    {
        $response = $this->get('/treasury/credit-note/1/download');

        $response->assertRedirect('/login');
    }

    /**
     * La descarga de reporte de sesión de caja requiere autenticación.
     */
    public function test_cash_session_report_requires_authentication(): void
    {
        $response = $this->get('/treasury/cash-session/1/report/z');

        $response->assertRedirect('/login');
    }

    /**
     * El parámetro 'type' del reporte de caja solo acepta 'x' o 'z'.
     * Otros valores deben retornar 404 (where constraint en rutas).
     */
    public function test_cash_session_report_type_constraint(): void
    {
        $cashier = User::factory()->create();
        $cashier->givePermissionTo('registrar-pagos');

        // 'a' no es un tipo válido, debe ser 404
        $response = $this->actingAs($cashier)
            ->get('/treasury/cash-session/1/report/a');

        $response->assertNotFound();
    }

    /**
     * La ruta de vouchers dentro del grupo treasury requiere el permiso correcto.
     */
    public function test_voucher_series_manager_requires_gestionar_correlativos(): void
    {
        Permission::firstOrCreate(['name' => 'gestionar-correlativos']);

        $userWithOnlyPayments = User::factory()->create();
        $userWithOnlyPayments->givePermissionTo('registrar-pagos');
        // NO tiene 'gestionar-correlativos'

        $response = $this->actingAs($userWithOnlyPayments)
            ->get('/treasury/voucher-series');

        $response->assertForbidden();
    }

    /**
     * Ruta de notas de crédito requiere permiso 'anular-comprobantes'.
     */
    public function test_credit_notes_requires_anular_comprobantes_permission(): void
    {
        $userWithOnlyPayments = User::factory()->create();
        $userWithOnlyPayments->givePermissionTo('registrar-pagos');
        // NO tiene 'anular-comprobantes'

        $response = $this->actingAs($userWithOnlyPayments)
            ->get('/treasury/credit-notes');

        $response->assertForbidden();
    }
}
