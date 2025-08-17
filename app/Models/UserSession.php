<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'session_id', 'ip_address', 'user_agent',
        'device', 'platform', 'browser', 'location', 'last_activity',
    ];

    protected $casts = [
        'last_activity' => 'datetime',
    ];
}


