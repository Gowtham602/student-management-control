<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentMessageTemplate extends Model
{
    use HasFactory;
     protected $fillable = [
        'name',
        'language',
        'template_id',
        'message',
        'status',
    ];
}
