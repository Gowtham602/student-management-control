<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Student::with('department','section')->get()->map(function ($student) {
            return [
                'rollnum'        => $student->rollnum,
                'name'           => $student->name,
                'email'          => $student->email,

                //  HUMAN READABLE
                'department'     => $student->department->code ?? '',
                'section'        => $student->section->name ?? '',

                'admission_year'=> $student->admission_year,
                'passout_year'  => $student->passout_year,
                'phone'         => $student->phone,
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
