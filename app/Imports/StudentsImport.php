<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\{
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsFailures
};

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    public int $inserted = 0;

    public function model(array $row)
    {
        $row = array_map('trim', array_change_key_case($row, CASE_LOWER));

         Student::updateOrCreate(
        ['rollnum' => $row['rollnum']],   // find by
        [
            'name'           => $row['name'],
            'email'          => $row['email'],
            'gender'         => $row['gender'],
            'phone'          => $row['phone'],
            'blood_group'    => $row['blood_group'] ?? null,
            'father_phone'   => $row['father_phone'],
            'department'    => $row['department'],
            'section'       => $row['section'],
            'academic_year' => $row['academic_year'],
            'passout_year'  => $row['passout_year'],
        ]
    );

        return null;
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|min:3',
            '*.email' => 'required|email|unique:students,email',
            '*.gender' => 'required|in:male,female,other',
            '*.rollnum' => 'required|unique:students,rollnum',
            '*.phone' => 'required|digits:10',
            '*.father_phone' => 'required|digits:10',
            '*.department' => 'required',
            '*.section' => 'required',
            '*.academic_year' => 'required|integer|between:2000,2100',
            '*.passout_year' => 'required|integer|between:2000,2100',
        ];
    }
}
