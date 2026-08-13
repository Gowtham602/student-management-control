<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentCommunicationRecipient extends Model
{
    protected $fillable = [
        'parent_communication_id',
        'student_id',
        'student_name',
        'parent_name',
        'phone',
        'message',
        'status',
        'sms_response',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function communication()
    {
        return $this->belongsTo(
            ParentCommunication::class,
            'parent_communication_id'
        );
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}