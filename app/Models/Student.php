<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students';

    protected $fillable = [
        'name',
        'email',
        'gender',
        'rollnum',
        'phone',
        'blood_group',
        'father_phone',
        'department',
        'admission_year',
        'current_year',
        'semester',
        'section',
        'academic_year',
        'passout_year',
    ];

    // Auto calculate study year
    public function getStudyYearAttribute()
    {
        if (!$this->admission_year) {
            return null;
        }

        $year = (now()->year - $this->admission_year) + 1;

        return match (true) {
            $year <= 1 => '1st Year',
            $year === 2 => '2nd Year',
            $year === 3 => '3rd Year',
            $year === 4 => 'Final Year',
            default => 'Passed Out',
        };
    }
}
