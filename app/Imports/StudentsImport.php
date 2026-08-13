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
        $row = array_map(
            'trim',
            array_change_key_case($row, CASE_LOWER)
        );

        /*
        |--------------------------------------------------------------------------
        | Semester
        |--------------------------------------------------------------------------
        */

        $semester = $this->convertSemester(
            $row['semester'] ?? ''
        );

        /*
        |--------------------------------------------------------------------------
        | Department
        |--------------------------------------------------------------------------
        */

        $departmentValue = trim($row['department'] ?? '');

        $department = Department::where('name', $departmentValue)
            ->orWhere('code', $departmentValue)
            ->first();

        if (!$department) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Section
        |--------------------------------------------------------------------------
        */

        $sectionValue = trim($row['section'] ?? '');

        $section = Section::where('department_id', $department->id)
            ->where('name', $sectionValue)
            ->first();

        if (!$section) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Gender
        |--------------------------------------------------------------------------
        */

        $gender = strtolower(trim($row['gender'] ?? ''));

        if (!in_array($gender, ['male', 'female', 'other'])) {
            $gender = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        */

        $student = Student::updateOrCreate(
            [
                'rollnum' => $row['rollnum'],
            ],
            [
                'name' => $row['name'],
                'email' => $row['email'] ?? null,
                'gender' => $gender,

                'phone' => $row['phone'] ?? null,
                'blood_group' => $row['blood_group'] ?? null,
                'father_phone' => $row['father_phone'] ?? null,

                'department_id' => $department->id,
                'section_id' => $section->id,

                'semester' => $semester,
            ]
        );

        if ($student->wasRecentlyCreated) {
            $this->inserted++;
        } else {
            $this->updated++;
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | Convert Semester
    |--------------------------------------------------------------------------
    */

    private function convertSemester($value): ?int
    {
        $value = strtolower(trim((string) $value));

        return match ($value) {

            '1',
            'i',
            'i semester',
            '1st semester'
                => 1,

            '2',
            'ii',
            'ii semester',
            '2nd semester'
                => 2,

            '3',
            'iii',
            'iii semester',
            '3rd semester'
                => 3,

            '4',
            'iv',
            'iv semester',
            '4th semester'
                => 4,

            '5',
            'v',
            'v semester',
            '5th semester'
                => 5,

            '6',
            'vi',
            'vi semester',
            '6th semester'
                => 6,

            '7',
            'vii',
            'vii semester',
            '7th semester'
                => 7,

            '8',
            'viii',
            'viii semester',
            '8th semester'
                => 8,

            default => null,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    */

    public function rules(): array
    {
        return [

            '*.rollnum' => 'required|distinct',

            '*.name' => 'required|min:3',

            '*.email' => 'nullable|email',

            '*.gender' => 'nullable|in:male,female,other',

            '*.phone' => 'nullable|digits:10',

            '*.father_phone' => 'nullable|digits:10',

            '*.department' => 'required',

            '*.section' => 'required',

            '*.semester' => 'required',
        ];
    }

    public function onError(Throwable $e)
    {
        // Skip database errors
    }
}