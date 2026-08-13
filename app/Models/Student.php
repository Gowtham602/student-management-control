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
        'department_id',
        'section_id',
        'semester',
    ];

    protected $casts = [
        'semester' => 'integer',
    ];

    /**
     * Display study year from semester.
     */
    public function getStudyYearAttribute(): string
    {
        return match (true) {
            in_array($this->semester, [1, 2]) => '1st Year',
            in_array($this->semester, [3, 4]) => '2nd Year',
            in_array($this->semester, [5, 6]) => '3rd Year',
            in_array($this->semester, [7, 8]) => 'Final Year',
            default => 'N/A',
        };
    }

    /**
     * Display semester name.
     */
    public function getSemesterNameAttribute(): string
    {
        return match ($this->semester) {
            1 => 'I Semester',
            2 => 'II Semester',
            3 => 'III Semester',
            4 => 'IV Semester',
            5 => 'V Semester',
            6 => 'VI Semester',
            7 => 'VII Semester',
            8 => 'VIII Semester',
            default => 'N/A',
        };
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}