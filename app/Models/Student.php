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
    if (!$this->admission_year || !$this->passout_year) {
        return null;
    }

    $currentYear = now()->year;

    // Passed out
    if ($currentYear > (int)$this->passout_year) {
        return 'Passed Out';
    }

    $year = ($currentYear - $this->admission_year) + 1;

    return match ($year) {
        1 => '1st Year',
        2 => '2nd Year',
        3 => '3rd Year',
        4 => 'Final Year',
        default => 'Passed Out',
    };
}

}
