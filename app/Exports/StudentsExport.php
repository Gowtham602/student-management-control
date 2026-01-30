<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Student::select(
            'rollnum',
            'name',
            'email',
            'department',
            'section',
            'academic_year',
            'passout_year',
            'phone'
        )->get();
    }

    public function headings(): array
    {
        return [
            'Roll No',
            'Name',
            'Email',
            'Department',
            'Section',
            'Academic Year',
            'Passout Year',
            'Phone',
        ];
    }
}
