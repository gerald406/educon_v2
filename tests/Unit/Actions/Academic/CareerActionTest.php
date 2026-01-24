<?php

namespace Tests\Unit\Actions\Academic;

use App\Actions\Academic\CreateCareer;
use App\Actions\Academic\UpdateCareer;
use App\Models\Career;
use App\Models\Institution;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CareerActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_career_action_creates_career()
    {
        $institution = Institution::factory()->create(['status' => 'active']);
        
        $input = [
            'institution_id' => $institution->id,
            'code' => 'SOFT-01',
            'name' => 'Software Engineering',
            'duration_semesters' => 10,
            'degree_awarded' => 'Engineer',
            'status' => 'active',
        ];

        $career = (new CreateCareer())->create($input);

        $this->assertInstanceOf(Career::class, $career);
        $this->assertDatabaseHas('careers', ['code' => 'SOFT-01']);
    }

    public function test_create_career_action_validates_required_fields()
    {
        $this->expectException(ValidationException::class);
        
        (new CreateCareer())->create([]);
    }

    public function test_update_career_action_updates_fields()
    {
        $institution = Institution::factory()->create(['status' => 'active']);
        $career = Career::factory()->create([
            'institution_id' => $institution->id,
            'name' => 'Old Name',
        ]);

        $input = [
            'institution_id' => $institution->id,
            'code' => $career->code,
            'name' => 'New Name',
            'duration_semesters' => $career->duration_semesters,
            'degree_awarded' => $career->degree_awarded,
            'status' => 'active',
        ];

        (new UpdateCareer())->update($career, $input);

        $this->assertDatabaseHas('careers', ['id' => $career->id, 'name' => 'New Name']);
    }

    public function test_update_career_allows_same_code()
    {
        $career = Career::factory()->create(['code' => 'CODE-1']);
        
        $input = [
            'institution_id' => $career->institution_id,
            'code' => 'CODE-1', // Same code
            'name' => 'New Name',
            'duration_semesters' => $career->duration_semesters,
            'degree_awarded' => $career->degree_awarded,
            'status' => 'active',
        ];

        // Should not throw validation exception
        (new UpdateCareer())->update($career, $input);
        
        $this->assertTrue(true);
    }
}
