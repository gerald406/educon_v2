<?php

namespace Tests\Feature\AcademicProcess;

use App\Livewire\Pages\AcademicProcess\AcademicPeriods\AcademicPeriodManager;
use App\Models\User;
use App\Models\AcademicPeriod;
use App\Models\AcademicYear;
use App\Models\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

/**
 * Pruebas del módulo de Procesos Académicos — Períodos Académicos.
 */
class AcademicPeriodTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'gestionar-periodos']);
        Permission::firstOrCreate(['name' => 'gestionar-carga-academica']);
        Permission::firstOrCreate(['name' => 'gestionar-horarios']);
        Permission::firstOrCreate(['name' => 'aprobar-silabos']);
        Permission::firstOrCreate(['name' => 'gestionar-reservas-matricula']);
        Permission::firstOrCreate(['name' => 'gestionar-reincorporaciones']);
        Permission::firstOrCreate(['name' => 'gestionar-matricula-regular']);
    }

    // --- Control de acceso ---

    public function test_academic_periods_requires_authentication(): void
    {
        $this->get('/academic-process/academic-periods')->assertRedirect('/login');
    }

    public function test_academic_periods_requires_correct_permission(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->get('/academic-process/academic-periods')->assertForbidden();
    }

    public function test_academic_periods_renders_with_permission(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-periodos');

        $this->actingAs($admin)->get('/academic-process/academic-periods')->assertOk();
    }

    public function test_teacher_assignments_requires_authentication(): void
    {
        $this->get('/academic-process/teacher-assignments')->assertRedirect('/login');
    }

    public function test_schedules_requires_authentication(): void
    {
        $this->get('/academic-process/schedules')->assertRedirect('/login');
    }

    public function test_syllabus_approval_requires_authentication(): void
    {
        $this->get('/academic-process/syllabus-approval')->assertRedirect('/login');
    }

    public function test_reservations_requires_authentication(): void
    {
        $this->get('/academic-process/reservations')->assertRedirect('/login');
    }

    public function test_regular_enrollment_requires_authentication(): void
    {
        $this->get('/academic-process/regular-enrollment')->assertRedirect('/login');
    }

    // --- Componente Livewire ---

    public function test_academic_period_manager_component_renders(): void
    {
        $admin = User::factory()->create();
        $admin->givePermissionTo('gestionar-periodos');

        Livewire::actingAs($admin)
            ->test(AcademicPeriodManager::class)
            ->assertStatus(200);
    }

    // --- Integridad del modelo AcademicPeriod ---

    public function test_academic_period_belongs_to_institution_and_year(): void
    {
        $institution = Institution::factory()->create();
        $year        = AcademicYear::factory()->create(['institution_id' => $institution->id]);

        $period = AcademicPeriod::factory()->create([
            'institution_id'   => $institution->id,
            'academic_year_id' => $year->id,
        ]);

        $this->assertInstanceOf(Institution::class, $period->institution);
        $this->assertInstanceOf(AcademicYear::class, $period->academicYear);
    }

    public function test_academic_period_soft_deletes(): void
    {
        $institution = Institution::factory()->create();
        $year        = AcademicYear::factory()->create(['institution_id' => $institution->id]);

        $period = AcademicPeriod::factory()->create([
            'institution_id'   => $institution->id,
            'academic_year_id' => $year->id,
        ]);

        $period->delete();

        $this->assertSoftDeleted('academic_periods', ['id' => $period->id]);
    }

    public function test_academic_period_fillable_fields(): void
    {
        $period = new AcademicPeriod();
        $fillable = $period->getFillable();

        $this->assertContains('institution_id', $fillable);
        $this->assertContains('academic_year_id', $fillable);
        $this->assertContains('code', $fillable);
        $this->assertContains('start_date', $fillable);
        $this->assertContains('end_date', $fillable);
        $this->assertContains('status', $fillable);
        // Campos de registro de notas
        $this->assertContains('grade_entry_start_date', $fillable);
        $this->assertContains('grade_entry_end_date', $fillable);
        // id no debe estar en fillable
        $this->assertNotContains('id', $fillable);
    }
}
