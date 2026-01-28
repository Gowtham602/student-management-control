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
        public int $updated = 0; 

    public function model(array $row)
    {
        $row = array_map('trim', array_change_key_case($row, CASE_LOWER));

       $student= Student::updateOrCreate(
            ['rollnum' => $row['rollnum']],   // ONLY CHECK ROLLNUM
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
        
        if ($student->wasRecentlyCreated) {
            $this->inserted++;
        } else {
            $this->updated++;
        }

        return null;
    }
    
    public function rules(): array
    {
        return [
            '*.rollnum' => 'required',
            '*.name' => 'required|min:3',
            '*.email' => 'required|email',
            '*.gender' => 'required|in:male,female,other',
            '*.phone' => 'required|digits:10',
            '*.father_phone' => 'required|digits:10',
            '*.department' => 'required',
            '*.section' => 'required',
            '*.academic_year' => 'required|integer|between:2000,2100',
            '*.passout_year' => 'required|integer|between:2000,2100',
        ];
    }
}
