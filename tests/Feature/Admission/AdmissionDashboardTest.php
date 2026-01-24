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
        $modality = AdmissionModality::firstOrCreate(['name' => 'Ordinario'], ['is_active' => true]);
        
        // Use factories if available, or create manually if complex dependencies
        // Assuming minimal requirements for Applicant
        Applicant::create([
            'admission_modality_id' => $modality->id,
            // Add other required fields based on migration schema if factory not fully covered
            // For now relying on factory if exist, else manual
            'created_at' => now(),
        ]);

        Applicant::create([
            'admission_modality_id' => $modality->id,
            'created_at' => now()->subDays(10), // Not recent
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
