<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedJob extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'source',
        'external_id',
        'title',
        'company_name',
        'location',
        'employment_type',
        'apply_url',
        'short_description',
        'tags',
        'saved_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'saved_at' => 'datetime',
    ];
}


