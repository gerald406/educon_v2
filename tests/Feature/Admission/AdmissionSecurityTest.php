<?php

namespace Tests\Feature\Admission;

use App\Models\User;
use App\Models\Applicant;
use App\Models\Institution;
use App\Models\AdmissionOffering;
use App\Models\AdmissionModality;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Pruebas de seguridad del módulo Admisión.
 *
 * BUG-007: Los PDFs de admisión no verifican si el applicant
 * pertenece a la institución activa del usuario.
 */
class AdmissionSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'gestionar-admision']);
    }

    // --- Control de acceso ---

    public function test_applicants_manager_requires_authentication(): void
    {
        $this->get('/admission/applicants')->assertRedirect('/login');
    }

    public function test_admission_dashboard_requires_authentication(): void
    {
        $this->get('/admission/dashboard')->assertRedirect('/login');
    }

    public function test_fast_grades_requires_authentication(): void
    {
        $this->get('/admission/fast-grades')->assertRedirect('/login');
    }

    public function test_user_without_admission_permission_cannot_access(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admission/applicants')->assertForbidden();
        $this->actingAs($user)->get('/admission/dashboard')->assertForbidden();
    }

    public function test_user_with_admission_permission_can_access_applicants(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-admision');

        $this->actingAs($admin)->get('/admission/applicants')->assertOk();
    }

    // --- BUG-007: Documentos PDF sin validación de institución ---

    /**
     * BUG-007: Las rutas /admission/constancia/{applicant} y /admission/ficha/{applicant}
     * solo verifican el permiso 'gestionar-admision' a nivel de ruta, pero no comprueban
     * si el applicant pertenece a la institución del usuario autenticado.
     *
     * Un administrador de una institución podría ver documentos de postulantes
     * de otra institución.
     */
    public function test_admission_document_requires_authentication(): void
    {
        $this->get('/admission/constancia/1')->assertRedirect('/login');
        $this->get('/admission/ficha/1')->assertRedirect('/login');
    }

    public function test_admission_document_requires_admission_permission(): void
    {
        // Crear un postulante mínimo válido para que el model binding resuelva
        $applicantUser = User::factory()->create();
        $applicant = \App\Models\Applicant::create([
            'user_id'            => $applicantUser->id,
            'gender'             => 'masculino',
            'birthday'           => '2000-01-01',
            'code'               => 'P2026-00001',
            'application_status' => 'registrado',
            'registration_step'  => 1,
        ]);

        $userWithoutPermission = User::factory()->create();
        // Sin permiso 'gestionar-admision'

        $response = $this->actingAs($userWithoutPermission)
            ->get("/admission/constancia/{$applicant->id}");
        $response->assertForbidden();
    }

    public function test_admission_document_returns_404_for_nonexistent_applicant(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-admision');

        // ID inexistente
        $response = $this->actingAs($admin)->get('/admission/constancia/99999');
        $response->assertNotFound();
    }

    // --- Rutas de configuración de admisión ---

    public function test_modalities_manager_requires_auth_and_permission(): void
    {
        $this->get('/admission/modalities')->assertRedirect('/login');

        $user = User::factory()->create();
        $this->actingAs($user)->get('/admission/modalities')->assertForbidden();
    }

    public function test_financial_entities_requires_auth_and_permission(): void
    {
        $this->get('/admission/financial-entities')->assertRedirect('/login');

        $user = User::factory()->create();
        $this->actingAs($user)->get('/admission/financial-entities')->assertForbidden();
    }

    public function test_offerings_requires_auth_and_permission(): void
    {
        $this->get('/admission/offerings')->assertRedirect('/login');

        $user = User::factory()->create();
        $this->actingAs($user)->get('/admission/offerings')->assertForbidden();
    }
}
