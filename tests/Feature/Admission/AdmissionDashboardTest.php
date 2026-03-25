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

        // BUG-FIX: 'type' es NOT NULL en admission_modalities — debe proporcionarse
        $modality = AdmissionModality::create([
            'name'      => 'Ordinario',
            'type'      => 'ordinario',
            'is_active' => true,
        ]);

        $applicantUser1 = User::factory()->create();
        $applicantUser2 = User::factory()->create();

        // BUG-FIX: 'gender' y 'birthday' son NOT NULL en applicants
        Applicant::create([
            'user_id'               => $applicantUser1->id,
            'admission_modality_id' => $modality->id,
            'gender'                => 'masculino',
            'birthday'              => '2000-01-01',
            'application_status'    => 'registrado',
            'registration_step'     => 1,
            'created_at'            => now(),
        ]);

        Applicant::create([
            'user_id'               => $applicantUser2->id,
            'admission_modality_id' => $modality->id,
            'gender'                => 'femenino',
            'birthday'              => '2001-05-15',
            'application_status'    => 'registrado',
            'registration_step'     => 1,
            'created_at'            => now()->subDays(10),
        ]);

        Livewire::actingAs($user)
            ->test(AdmissionDashboard::class)
            ->assertSet('totalApplicants', 2);
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
