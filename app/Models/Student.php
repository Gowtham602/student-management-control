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
        'semester',
        'section',
        'passout_year',
    ];

    // AUTO CURRENT STUDY YEAR
    public function getStudyYearAttribute(): string
    {
        if (!$this->admission_year || !$this->passout_year) {
            return 'N/A';
        }

        $currentYear = now()->year;

        if ($currentYear < $this->admission_year) {
            return 'Not Started';
        }

        if ($currentYear > $this->passout_year) {
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
