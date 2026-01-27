<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\{
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsFailures
};

class StudentsImport implements
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure
{
    use SkipsFailures;

    /**
     * INSERT / UPDATE LOGIC
     */
    public function model(array $row)
    {
        return Student::updateOrCreate(
            ['email' => $row['email']], // UNIQUE KEY
            [
                'name'          => $row['name'],
                'gender'        => $row['gender'],
                'rollnum'       => $row['rollnum'],
                'phone'         => $row['phone'],
                'blood_group'   => $row['blood_group'] ?? null,
                'father_phone'  => $row['father_phone'],
                'department'    => $row['department'],
                'section'       => $row['section'],
                'academic_year' => $row['academic_year'],
                'passout_year'  => $row['passout_year'],
            ]
        );
    }

    /**
     * ROW VALIDATION (NO *)
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|min:3',
            'email'         => 'required|email',
            'gender'        => 'required|in:male,female,other',
            'rollnum'       => 'required',
            'phone'         => 'required|digits:10',
            'father_phone'  => 'required|digits:10',
            'department'    => 'required',
            'section'       => 'required',
            'academic_year' => 'required|digits:4',
            'passout_year'  => 'required|digits:4',
        ];
    }
}
