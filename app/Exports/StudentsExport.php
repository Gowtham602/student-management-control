<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    protected Collection $students;

    public function __construct(Collection $students)
    {
        $this->students = $students;
    }

    public function collection()
    {
        return $this->students->map(function ($student) {
            return [
                $student->rollnum,
                $student->name,
                $student->email,

                // Human readable
                $student->department->code ?? '',
                $student->section->name ?? '',

                $student->admission_year,
                $student->passout_year,
                $student->phone,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Roll No',
            'Name',
            'Email',
            'Department',
            'Section',
            'Admission Year',
            'Passout Year',
            'Phone',
        ];
    }
}
