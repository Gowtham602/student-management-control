<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatedStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
        'name'          => 'required|string|min:3',
        'email'         => 'required|email|unique:students,email,' . $this->student->id,
        'gender'        => 'required',
        'rollnum'       => 'required|unique:students,rollnum,' . $this->student->id,
        'phone'         => 'required|digits:10',
        'father_phone'  => 'required|digits:10',
         'department_id' => 'required|exists:departments,id',
            'section_id' => 'required|exists:sections,id',
        'admission_year'=> 'required',
         
        'passout_year'  =>'required',
    ];
    }
}
