<?php

namespace Tests\Feature\Admission;

use App\Http\Controllers\ExamAttendanceController;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class AdmissionSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Permission::firstOrCreate(['name' => 'gestionar-admision']);
    }

    // Public exam kiosk routes (intentional — no auth required)
    public function test_exam_attendance_kiosk_is_publicly_accessible()
    {
        $response = $this->get('/admission/exam/attendance');
        // Should NOT redirect to login (it's a public kiosk)
        $response->assertStatus(200);
    }

    public function test_exam_attendance_search_is_publicly_accessible()
    {
        $response = $this->postJson('/admission/exam/attendance/search', [
            'dni' => '12345678',
        ]);
        // Public endpoint, should not require auth
        $response->assertStatus(200);
    }

    // Protected admission management routes
    public function test_admission_management_requires_auth()
    {
        $response = $this->get('/admission/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_admission_management_requires_permission()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admission/dashboard');
        $response->assertForbidden();
    }

    public function test_exam_attendance_route_names_exist()
    {
        $this->assertTrue(
            \Route::has('admission.exam.attendance'),
            'Route admission.exam.attendance must exist'
        );
        $this->assertTrue(
            \Route::has('admission.exam.attendance.search'),
            'Route admission.exam.attendance.search must exist'
        );
    }
}
