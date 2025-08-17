<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id', 'sender_id', 'sender_type', 'body', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];
}


