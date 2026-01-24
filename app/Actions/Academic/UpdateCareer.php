<?php

namespace App\Actions\Academic;

use App\Models\Career;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UpdateCareer
{
    /**
     * Validate and update the given career.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(Career $career, array $input): void
    {
        Validator::make($input, [
            'institution_id' => ['required', 'exists:institutions,id'],
            'name' => ['required', 'string', 'max:150'],
            'duration_semesters' => ['required', 'integer', 'min:1', 'max:12'],
            'degree_awarded' => ['nullable', 'string', 'max:200'],
            'status' => ['required', 'in:active,inactive'],
            'code' => [
                'required', 
                'string', 
                'max:10', 
                Rule::unique('careers', 'code')->ignore($career->id),
            ],
        ])->validate();

        $career->forceFill([
            'institution_id' => $input['institution_id'],
            'name' => $input['name'],
            'duration_semesters' => $input['duration_semesters'],
            'degree_awarded' => $input['degree_awarded'],
            'status' => $input['status'],
            'code' => $input['code'],
        ])->save();
    }
}
