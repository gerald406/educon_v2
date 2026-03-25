<?php

namespace Tests\Feature\Security;

use App\Models\User;
use App\Models\Voucher;
use App\Models\CashSession;
use App\Models\CreditNote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

/**
 * Pruebas de protección de rutas.
 *
 * Verifica que todas las rutas sensibles requieran autenticación
 * y los permisos correctos. Detecta BUG-001 y BUG-002.
 */
class RouteProtectionTest extends TestCase
{
    use RefreshDatabase;

    // --- Módulo: Configuración ---

    public function test_settings_requires_authentication(): void
    {
        $this->get('/settings/classrooms')->assertRedirect('/login');
        $this->get('/settings/academic-years')->assertRedirect('/login');
        $this->get('/settings/institution')->assertRedirect('/login');
    }

    public function test_settings_requires_correct_permission(): void
    {
        Permission::firstOrCreate(['name' => 'gestionar-configuracion']);
        $user = User::factory()->create(); // Sin permisos

        $this->actingAs($user)->get('/settings/classrooms')->assertForbidden();
    }

    // --- Módulo: Académico ---

    public function test_academic_routes_require_authentication(): void
    {
        $this->get('/academic/careers')->assertRedirect('/login');
        $this->get('/academic/study-plans')->assertRedirect('/login');
        $this->get('/academic/modules')->assertRedirect('/login');
    }

    public function test_academic_routes_require_correct_permission(): void
    {
        Permission::firstOrCreate(['name' => 'gestionar-estructura-academica']);
        $user = User::factory()->create();

        $this->actingAs($user)->get('/academic/careers')->assertForbidden();
    }

    // --- Módulo: Personas ---

    public function test_people_routes_require_authentication(): void
    {
        $this->get('/people/teachers')->assertRedirect('/login');
        $this->get('/people/students')->assertRedirect('/login');
    }

    public function test_people_routes_require_correct_permission(): void
    {
        Permission::firstOrCreate(['name' => 'gestionar-docentes']);
        Permission::firstOrCreate(['name' => 'gestionar-estudiantes']);
        $user = User::factory()->create();

        $this->actingAs($user)->get('/people/teachers')->assertForbidden();
        $this->actingAs($user)->get('/people/students')->assertForbidden();
    }

    // --- Módulo: Procesos Académicos ---

    public function test_academic_process_routes_require_authentication(): void
    {
        $this->get('/academic-process/academic-periods')->assertRedirect('/login');
        $this->get('/academic-process/teacher-assignments')->assertRedirect('/login');
        $this->get('/academic-process/schedules')->assertRedirect('/login');
    }

    // --- Módulo: Evaluación ---

    public function test_evaluation_routes_require_authentication(): void
    {
        $this->get('/evaluation/grades')->assertRedirect('/login');
        $this->get('/evaluation/attendances')->assertRedirect('/login');
    }

    public function test_evaluation_requires_teacher_or_higher_role(): void
    {
        Role::firstOrCreate(['name' => 'Estudiante']);
        Permission::firstOrCreate(['name' => 'matricularse']);
        $student = User::factory()->create();
        $student->assignRole('Estudiante');
        $student->givePermissionTo('matricularse');

        $this->actingAs($student)->get('/evaluation/grades')->assertForbidden();
        $this->actingAs($student)->get('/evaluation/attendances')->assertForbidden();
    }

    // --- Módulo: Tesorería ---

    public function test_treasury_routes_require_authentication(): void
    {
        $this->get('/treasury/payments')->assertRedirect('/login');
        $this->get('/treasury/cash-sessions')->assertRedirect('/login');
        $this->get('/treasury/voucher-series')->assertRedirect('/login');
    }

    public function test_treasury_routes_require_correct_permission(): void
    {
        Permission::firstOrCreate(['name' => 'registrar-pagos']);
        $user = User::factory()->create();

        $this->actingAs($user)->get('/treasury/payments')->assertForbidden();
    }

    /**
     * BUG-001: Ruta duplicada /voucher/{id}/download sin middleware de autenticación.
     * CORREGIDO: La ruta pública fue eliminada de routes/web.php.
     *
     * Verifica que la ruta pública ya no existe (404) y que la ruta treasury protegida
     * sí redirige a login cuando no hay sesión.
     */
    public function test_voucher_download_requires_authentication(): void
    {
        // La ruta pública /voucher/{id}/download fue eliminada (BUG-001 corregido).
        // Debe retornar 404 (ruta no registrada), NO 200.
        $publicResponse = $this->get('/voucher/1/download');
        $publicResponse->assertNotFound();

        // La ruta dentro del grupo treasury SÍ debe redirigir a login cuando no hay auth.
        $protectedResponse = $this->get('/treasury/voucher/1/download');
        $protectedResponse->assertRedirect('/login');
    }

    /**
     * BUG-002: VoucherController::download() no verifica propiedad del voucher.
     * Un usuario autenticado podría ver comprobantes de otros usuarios.
     */
    public function test_voucher_download_checks_ownership_or_permission(): void
    {
        Permission::firstOrCreate(['name' => 'registrar-pagos']);

        $owner  = User::factory()->create();
        $other  = User::factory()->create();

        // Crear un comprobante asociado al usuario 'owner' como emisor
        $voucher = Voucher::factory()->create([
            'issuer_id' => $owner->id,
            'client_id' => $owner->id,
        ]);

        // El usuario 'other' (sin permiso) NO debe poder acceder al voucher dentro del grupo treasury
        $response = $this->actingAs($other)->get("/treasury/voucher/{$voucher->id}/download");

        // Sin el permiso 'registrar-pagos', debe obtener 403
        $response->assertForbidden();
    }

    // --- Módulo: Matrícula ---

    public function test_enrollment_routes_require_authentication(): void
    {
        $this->get('/enrollment/process')->assertRedirect('/login');
    }

    public function test_enrollment_requires_student_or_admin_role(): void
    {
        Role::firstOrCreate(['name' => 'Docente']);
        Permission::firstOrCreate(['name' => 'registrar-notas']);
        $teacher = User::factory()->create();
        $teacher->assignRole('Docente');
        $teacher->givePermissionTo('registrar-notas');

        // Un Docente NO debería acceder a la matrícula de estudiantes
        $this->actingAs($teacher)->get('/enrollment/process')->assertForbidden();
    }

    // --- Módulo: Admisión ---

    public function test_admission_routes_require_authentication(): void
    {
        $this->get('/admission/applicants')->assertRedirect('/login');
        $this->get('/admission/dashboard')->assertRedirect('/login');
    }

    public function test_admission_requires_correct_permission(): void
    {
        Permission::firstOrCreate(['name' => 'gestionar-admision']);
        $user = User::factory()->create();

        $this->actingAs($user)->get('/admission/applicants')->assertForbidden();
    }

    // --- Módulo: Seguridad (Roles y Usuarios) ---

    public function test_security_routes_require_authentication(): void
    {
        $this->get('/security/roles')->assertRedirect('/login');
        $this->get('/security/users')->assertRedirect('/login');
    }

    public function test_security_routes_require_correct_permission(): void
    {
        Permission::firstOrCreate(['name' => 'gestionar-roles']);
        Permission::firstOrCreate(['name' => 'gestionar-usuarios']);
        $user = User::factory()->create();

        $this->actingAs($user)->get('/security/roles')->assertForbidden();
    }

    // --- Módulo: Reportes ---

    public function test_reports_require_authentication(): void
    {
        $this->get('/reports')->assertRedirect('/login');
    }

    public function test_reports_require_admin_role(): void
    {
        Role::firstOrCreate(['name' => 'Docente']);
        $teacher = User::factory()->create();
        $teacher->assignRole('Docente');

        $this->actingAs($teacher)->get('/reports')->assertForbidden();
    }

    // --- Módulo: Servicios ---

    public function test_services_routes_require_authentication(): void
    {
        $this->get('/services/library-resources')->assertRedirect('/login');
        $this->get('/services/tutorings')->assertRedirect('/login');
    }

    // --- Módulo: Comunicación ---

    public function test_communication_routes_require_authentication(): void
    {
        $this->get('/communication/announcements')->assertRedirect('/login');
    }

    // --- Módulo: Docente ---

    public function test_teacher_routes_require_authentication(): void
    {
        $this->get('/teacher/my-syllabi')->assertRedirect('/login');
        $this->get('/teacher/activities')->assertRedirect('/login');
    }

    public function test_teacher_routes_block_students(): void
    {
        Role::firstOrCreate(['name' => 'Estudiante']);
        $student = User::factory()->create();
        $student->assignRole('Estudiante');

        $this->actingAs($student)->get('/teacher/my-syllabi')->assertForbidden();
    }
}
