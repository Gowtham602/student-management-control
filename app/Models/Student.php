<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
     protected $fillable = [
    'name',
    'email',
    'gender',
    'rollnum',
    'phone',
    'blood_group',
    'father_phone',
    'department',
    'section',
    'academic_year',
    'passout_year',
];

}
