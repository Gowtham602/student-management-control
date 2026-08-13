<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentCommunication extends Model
{
    protected $fillable = [
        'created_by',
        'department_id',
        'section_id',
        'academic_year_id',
        'template_id',
        'subject',
        'message',
        'status',
        'submitted_at',
        'confirmed_by',
        'confirmed_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'total_students',
        'total_sent',
        'total_failed',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

  
   

    public function department()
{
    return $this->belongsTo(Department::class);
}

public function section()
{
    return $this->belongsTo(Section::class);
}

public function template()
{
    return $this->belongsTo(
        ParentMessageTemplate::class,
        'template_id'
    );
}

public function recipients()
{
    return $this->hasMany(
        ParentCommunicationRecipient::class
    );
}
}