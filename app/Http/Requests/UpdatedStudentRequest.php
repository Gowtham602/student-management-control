<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        'email' => [
    'nullable',
    'email',
    Rule::unique('students', 'email')->ignore($this->route('student')->id),
],
        'gender' => 'nullable|in:male,female,other',
      'rollnum' => [
    'required',
    Rule::unique('students', 'rollnum')->ignore($this->route('student')->id),
],
        'phone'         => 'nullable|digits:10',
        'father_phone'  => 'required|digits:10',
         'department_id' => 'required|exists:departments,id',
            'section_id' => 'required|exists:sections,id',
        'admission_year'=> 'required',
         
        'passout_year'  =>'required',
    ];
    }
}
