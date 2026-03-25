<?php

namespace Tests\Feature\Admission\Exam;

use App\Livewire\Pages\Admission\Exam\DistributionManager;
use App\Models\AdmissionModality;
use App\Models\AdmissionOffering;
use App\Models\Applicant;
use App\Models\Career;
use App\Models\ExamClassroom;
use App\Models\ExamClassroomAssignment;
use App\Models\ExamPavilion;
use App\Models\Shift;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DistributionManagerTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'gestionar-admision']);
        Role::firstOrCreate(['name' => 'Estudiante']); // requerido por ApplicantFactory::afterCreating
        $this->adminUser = User::factory()->create();
        $this->adminUser->givePermissionTo('gestionar-admision');
    }

    // ─── Helpers ───────────────────────────────────────────────

    private function makeApplicant(array $overrides = []): Applicant
    {
        return Applicant::factory()->create(array_merge([
            'application_status' => 'registrado',
        ], $overrides));
    }

    private function makeClassroom(int $capacity = 30): ExamClassroom
    {
        return ExamClassroom::factory()->create(['capacity' => $capacity, 'is_active' => true]);
    }

    // ─── Renderizado y acceso ───────────────────────────────────

    public function test_component_renders_for_authorized_user(): void
    {
        Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->assertStatus(200);
    }

    public function test_route_requires_authentication(): void
    {
        $this->get('/admission/exam/distribution')->assertRedirect('/login');
    }

    // ─── Filtros ────────────────────────────────────────────────

    public function test_filter_by_modality_narrows_results(): void
    {
        $mod1 = AdmissionModality::factory()->create(['is_active' => true]);
        $mod2 = AdmissionModality::factory()->create(['is_active' => true]);

        $this->makeApplicant(['admission_modality_id' => $mod1->id]);
        $this->makeApplicant(['admission_modality_id' => $mod2->id]);

        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('filterModality', $mod1->id);

        $component->assertSee($mod1->name);
        $this->assertEquals(1, $component->get('totalFiltered'));
    }

    public function test_filter_by_career_narrows_results(): void
    {
        $career1  = Career::factory()->create(['status' => 'active']);
        $career2  = Career::factory()->create(['status' => 'active']);
        $shift    = Shift::factory()->create();

        $offering1 = AdmissionOffering::factory()->create(['career_id' => $career1->id, 'shift_id' => $shift->id]);
        $offering2 = AdmissionOffering::factory()->create(['career_id' => $career2->id, 'shift_id' => $shift->id]);

        $this->makeApplicant(['admission_offering_id' => $offering1->id]);
        $this->makeApplicant(['admission_offering_id' => $offering2->id]);

        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('filterCareer', $career1->id);

        $this->assertEquals(1, $component->get('totalFiltered'));
    }

    public function test_filter_by_shift_narrows_results(): void
    {
        $career  = Career::factory()->create(['status' => 'active']);
        $shift1  = Shift::factory()->create();
        $shift2  = Shift::factory()->create();

        $offering1 = AdmissionOffering::factory()->create(['career_id' => $career->id, 'shift_id' => $shift1->id]);
        $offering2 = AdmissionOffering::factory()->create(['career_id' => $career->id, 'shift_id' => $shift2->id]);

        $this->makeApplicant(['admission_offering_id' => $offering1->id]);
        $this->makeApplicant(['admission_offering_id' => $offering2->id]);

        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('filterShift', $shift1->id);

        $this->assertEquals(1, $component->get('totalFiltered'));
    }

    public function test_search_by_document_number(): void
    {
        $user1 = User::factory()->create(['document_number' => '11111111']);
        $user2 = User::factory()->create(['document_number' => '22222222']);

        $this->makeApplicant(['user_id' => $user1->id]);
        $this->makeApplicant(['user_id' => $user2->id]);

        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('search', '11111111');

        $this->assertEquals(1, $component->get('totalFiltered'));
    }

    public function test_search_by_name(): void
    {
        $user1 = User::factory()->create(['lastname' => 'García', 'name' => 'Juan']);
        $user2 = User::factory()->create(['lastname' => 'López',  'name' => 'María']);

        $this->makeApplicant(['user_id' => $user1->id]);
        $this->makeApplicant(['user_id' => $user2->id]);

        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('search', 'García');

        $this->assertEquals(1, $component->get('totalFiltered'));
    }

    public function test_only_shows_unassigned_applicants(): void
    {
        $classroom  = $this->makeClassroom();
        $assigned   = $this->makeApplicant();
        $unassigned = $this->makeApplicant();

        ExamClassroomAssignment::create([
            'exam_classroom_id' => $classroom->id,
            'applicant_id'      => $assigned->id,
            'assigned_at'       => now(),
        ]);

        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class);

        $this->assertEquals(1, $component->get('totalFiltered'));
    }

    // ─── Selección ──────────────────────────────────────────────

    public function test_select_all_on_page_loads_current_page_ids(): void
    {
        $this->makeApplicant();
        $this->makeApplicant();

        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('selectAll', true);

        $this->assertCount(2, $component->get('selectedApplicants'));
    }

    public function test_deselect_all_clears_selection(): void
    {
        $this->makeApplicant();

        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('selectAll', true)
            ->set('selectAll', false);

        $this->assertEmpty($component->get('selectedApplicants'));
        $this->assertFalse($component->get('selectAllMode'));
    }

    public function test_activate_select_all_mode(): void
    {
        $this->makeApplicant();
        $this->makeApplicant();

        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->call('activateSelectAllMode');

        $this->assertTrue($component->get('selectAllMode'));
        $this->assertEmpty($component->get('selectedApplicants'));
    }

    public function test_clear_selection_resets_all_flags(): void
    {
        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->call('activateSelectAllMode')
            ->call('clearSelection');

        $this->assertFalse($component->get('selectAllMode'));
        $this->assertFalse($component->get('selectAll'));
        $this->assertEmpty($component->get('selectedApplicants'));
    }

    // ─── Asignación manual ──────────────────────────────────────

    public function test_assign_manual_requires_classroom(): void
    {
        $applicant = $this->makeApplicant();

        Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('selectedApplicants', [(string) $applicant->id])
            ->call('assignManual')
            ->assertHasErrors(['targetClassroom']);
    }

    public function test_assign_manual_assigns_selected_applicants(): void
    {
        $classroom = $this->makeClassroom(30);
        $a1 = $this->makeApplicant();
        $a2 = $this->makeApplicant();

        Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('selectedApplicants', [(string) $a1->id, (string) $a2->id])
            ->set('targetClassroom', $classroom->id)
            ->call('assignManual')
            ->assertHasNoErrors()
            ->assertDispatched('swal');

        $this->assertDatabaseHas('exam_classroom_assignments', [
            'applicant_id'      => $a1->id,
            'exam_classroom_id' => $classroom->id,
        ]);
        $this->assertDatabaseHas('exam_classroom_assignments', [
            'applicant_id'      => $a2->id,
            'exam_classroom_id' => $classroom->id,
        ]);
    }

    public function test_assign_manual_with_select_all_mode(): void
    {
        $classroom = $this->makeClassroom(50);
        $this->makeApplicant();
        $this->makeApplicant();
        $this->makeApplicant();

        Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->call('activateSelectAllMode')
            ->set('targetClassroom', $classroom->id)
            ->call('assignManual')
            ->assertHasNoErrors()
            ->assertDispatched('swal');

        $this->assertEquals(3, ExamClassroomAssignment::count());
    }

    public function test_assign_manual_rejects_when_capacity_exceeded(): void
    {
        $classroom = $this->makeClassroom(1); // solo 1 espacio
        $a1 = $this->makeApplicant();
        $a2 = $this->makeApplicant();

        Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('selectedApplicants', [(string) $a1->id, (string) $a2->id])
            ->set('targetClassroom', $classroom->id)
            ->call('assignManual')
            ->assertDispatched('swal');

        // No se asignó nada
        $this->assertEquals(0, ExamClassroomAssignment::count());
    }

    public function test_assign_manual_no_double_assignment(): void
    {
        $classroom = $this->makeClassroom(30);
        $applicant = $this->makeApplicant();

        // Primera asignación
        ExamClassroomAssignment::create([
            'exam_classroom_id' => $classroom->id,
            'applicant_id'      => $applicant->id,
            'assigned_at'       => now(),
        ]);

        // Intento de reasignar el mismo (en modo selectAllMode, el applicant ya tiene asignación
        // y no aparece en la query — pero probamos el guard interno)
        $classroom2 = $this->makeClassroom(30);

        Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('selectedApplicants', [(string) $applicant->id])
            ->set('targetClassroom', $classroom2->id)
            ->call('assignManual');

        // Solo debe existir 1 asignación (la original)
        $this->assertEquals(1, ExamClassroomAssignment::count());
    }

    // ─── Distribución automática ────────────────────────────────

    public function test_auto_distribute_assigns_all_pending_applicants(): void
    {
        $classroom = $this->makeClassroom(50);
        $this->makeApplicant();
        $this->makeApplicant();
        $this->makeApplicant();

        Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->call('autoDistribute')
            ->assertDispatched('swal');

        $this->assertEquals(3, ExamClassroomAssignment::count());
    }

    public function test_auto_distribute_respects_capacity(): void
    {
        $this->makeClassroom(2); // solo 2 espacios
        $this->makeApplicant();
        $this->makeApplicant();
        $this->makeApplicant(); // este no cabe

        Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->call('autoDistribute');

        $this->assertEquals(2, ExamClassroomAssignment::count());
    }

    public function test_auto_distribute_warns_when_no_applicants(): void
    {
        $this->makeClassroom();

        Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->call('autoDistribute')
            ->assertDispatched('swal');

        $this->assertEquals(0, ExamClassroomAssignment::count());
    }

    public function test_auto_distribute_warns_when_no_classrooms(): void
    {
        $this->makeApplicant();

        Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->call('autoDistribute')
            ->assertDispatched('swal');

        $this->assertEquals(0, ExamClassroomAssignment::count());
    }

    // ─── Reset ──────────────────────────────────────────────────

    public function test_reset_distribution_removes_all_assignments(): void
    {
        $classroom = $this->makeClassroom();
        $applicant = $this->makeApplicant();

        ExamClassroomAssignment::create([
            'exam_classroom_id' => $classroom->id,
            'applicant_id'      => $applicant->id,
            'assigned_at'       => now(),
        ]);

        Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->call('resetDistribution')
            ->assertDispatched('swal');

        $this->assertEquals(0, ExamClassroomAssignment::count());
    }

    // ─── Paginación ─────────────────────────────────────────────

    public function test_default_per_page_is_25(): void
    {
        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class);

        $this->assertEquals(25, $component->get('perPage'));
    }

    public function test_per_page_change_resets_selection(): void
    {
        $this->makeApplicant();

        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class)
            ->set('selectAll', true)
            ->set('perPage', 50);

        $this->assertEmpty($component->get('selectedApplicants'));
    }

    // ─── Estadísticas ───────────────────────────────────────────

    public function test_stats_reflect_assigned_count(): void
    {
        $classroom = $this->makeClassroom(30);
        $applicant = $this->makeApplicant();

        ExamClassroomAssignment::create([
            'exam_classroom_id' => $classroom->id,
            'applicant_id'      => $applicant->id,
            'assigned_at'       => now(),
        ]);

        $component = Livewire::actingAs($this->adminUser)
            ->test(DistributionManager::class);

        $this->assertEquals(1, $component->get('distributedCount'));
        $this->assertEquals(30, $component->get('totalCapacity'));
    }
}
