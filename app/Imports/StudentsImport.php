<?php
namespace App\Imports;

use App\Models\Student;
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

        $student = Student::updateOrCreate(
            ['rollnum' => $row['rollnum']],
            [
                'name'           => $row['name'],
                'email'          => $row['email'],
                'gender'         => $row['gender'],
                'phone'          => $row['phone'],
                'blood_group'    => $row['blood_group'] ?? null,
                'father_phone'   => $row['father_phone'],
                'department'    => $row['department'],
                'section'       => $row['section'],
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
            '*.email' => 'required|email',
            '*.gender' => 'required|in:male,female,other',
            '*.phone' => 'required|digits:10',
            '*.father_phone' => 'required|digits:10',
            '*.department' => 'required',
            '*.section' => 'required',
            '*.admission_year' => 'required|integer|between:2000,2100',
            '*.passout_year' => 'required|integer|between:2000,2100',
        ];
    }

    // Optional: customize DB error message
    public function onError(Throwable $e)
    {
        // silently skip DB duplicate errors instead of crashing
    }
}
