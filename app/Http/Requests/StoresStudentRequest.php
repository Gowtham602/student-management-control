<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoresStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|min:3',
            'email'          => 'required|email|unique:students,email',
            'gender'         => 'required|in:male,female,other',
            'rollnum'        => 'required|string|unique:students,rollnum',
            'phone'          => 'required|digits:10',
            'father_phone'   => 'required|digits:10',
            'department'     => 'required|string',
            'section'        => 'required|string',

            //  YEAR FIELDS (CORRECT)
            // 'admission_year' => 'required|integer|min:2000|max:' . now()->year,
            'admission_year' => 'required|integer|min:1950|max:' . (date('Y') + 1),
            // 'academic_year'  => 'required|string',     // ex: 2025-2026
            'passout_year'   => 'required|integer|gte:admission_year',
        ];
    }
}
