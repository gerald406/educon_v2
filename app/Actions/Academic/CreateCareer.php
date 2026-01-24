<?php

namespace App\Actions\Academic;

use App\Models\Career;
use Illuminate\Support\Facades\Validator;

class CreateCareer
{
    /**
     * Validate and create a newly registered career.
     *
     * @param  array<string, mixed>  $input
     */
    public function create(array $input): Career
    {
        Validator::make($input, [
            'institution_id' => ['required', 'exists:institutions,id'],
            'name' => ['required', 'string', 'max:150'],
            'duration_semesters' => ['required', 'integer', 'min:1', 'max:12'],
            'degree_awarded' => ['nullable', 'string', 'max:200'],
            'status' => ['required', 'in:active,inactive'],
            'code' => ['required', 'string', 'max:10', 'unique:careers,code'],
        ])->validate();

        return Career::create([
            'institution_id' => $input['institution_id'],
            'name' => $input['name'],
            'duration_semesters' => $input['duration_semesters'],
            'degree_awarded' => $input['degree_awarded'],
            'status' => $input['status'],
            'code' => $input['code'],
        ]);
    }
}
