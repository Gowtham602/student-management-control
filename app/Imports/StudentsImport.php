<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Department;
use App\Models\Section;
use Throwable;
use Maatwebsite\Excel\Concerns\{
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsFailures,
    SkipsOnError,
    SkipsErrors
};

class StudentsImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    SkipsOnError
{
    use SkipsFailures, SkipsErrors;

    public int $inserted = 0;
    public int $updated = 0;

    public function model(array $row)
    {
        $row = array_map('trim', array_change_key_case($row, CASE_LOWER));


         $gender = strtolower(trim($row['gender'] ?? ''));
        if (!in_array($gender, ['male', 'female', 'other'])) {
        $gender = null;
        }
        //  Find department by CODE (CSE, ECE, MECH)
        $department = Department::where('code', $row['department'])->first();

        if (!$department) {
            return null; // skip invalid department
        }

        //  Find section under that department
        $section = Section::where('department_id', $department->id)
                          ->where('name', $row['section'])
                          ->first();

        if (!$section) {
            return null; // skip invalid section
        }

        $student = Student::updateOrCreate(
            ['rollnum' => $row['rollnum']],
            [
                'name'           => $row['name'],
                'email'          => $row['email'] ?? null,
                // 'gender'         => $row['gender'] ?? null,
                'gender'         => $gender,
                'phone'          => $row['phone'] ?? null,
                'blood_group'    => $row['blood_group'] ?? null,
                'father_phone'   => $row['father_phone'],

                //  RELATIONAL SAVE
                'department_id'  => $department->id,
                'section_id'     => $section->id ,

                'admission_year'=> $row['admission_year'],
                'passout_year'  => $row['passout_year'],
            ]
        );

        $student->wasRecentlyCreated
            ? $this->inserted++
            : $this->updated++;

        return null;
    }

    public function rules(): array
    {
        return [
            '*.rollnum' => 'required|distinct',
            '*.name' => 'required|min:3',
            // '*.email' => 'required|email',
            // '*.gender' => 'required|in:male,female,other',
            // '*.phone' => 'required|digits:10',
            // Optional fields (validate only if value exists)
            '*.email'          => 'nullable|email',
            '*.gender'         => 'nullable|in:male,female,other',
            '*.phone'          => 'nullable|digits:10',
            '*.father_phone' => 'required|digits:10',
            '*.department' => 'required',
            '*.section' => 'required',
            '*.admission_year' => 'required|integer|between:1950,2100',
            '*.passout_year' => 'required|integer|between:1950,2100',
        ];
    }

    public function onError(Throwable $e)
    {
        // skip bad rows silently
    }
}
