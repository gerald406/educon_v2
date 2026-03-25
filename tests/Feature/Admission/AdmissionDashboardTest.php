<?php

namespace Tests\Feature\Admission;

use App\Livewire\Pages\Admission\AdmissionDashboard;
use App\Models\AdmissionModality;
use App\Models\AdmissionOffering;
use App\Models\Applicant;
use App\Models\Career;
use App\Models\Institution;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AdmissionDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'gestionar-admision']);
        \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Estudiante']);
    }

    public function test_admission_dashboard_can_render()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-admision');

        Livewire::actingAs($user)
            ->test(AdmissionDashboard::class)
            ->assertStatus(200);
    }

    public function test_metrics_are_calculated_correctly()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-admision');

        // Setup data
        $modality = AdmissionModality::firstOrCreate(
            ['name' => 'Ordinario'],
            ['is_active' => true, 'type' => 'ordinario']
        );

        Applicant::factory()->create([
            'admission_modality_id' => $modality->id,
            'created_at' => now(),
        ]);

        Applicant::factory()->create([
            'admission_modality_id' => $modality->id,
            'created_at' => now()->subDays(10),
        ]);

        Livewire::actingAs($user)
            ->test(AdmissionDashboard::class)
            ->assertSet('totalApplicants', 2)
            ->assertSet('recentRegistrations', 1);
    }

    public function test_chart_data_structure()
    {
        $user = User::factory()->create();
        $user->givePermissionTo('gestionar-admision');

        Livewire::actingAs($user)
            ->test(AdmissionDashboard::class)
            ->assertViewHas('applicantsByModality')
            ->assertViewHas('applicantsByProgram');
    }
}
