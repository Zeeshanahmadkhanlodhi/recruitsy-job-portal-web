<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'location',
        'industry',
        'website',
        'hr_portal_url',
        'api_key',
        'api_secret',
        'is_active',
        'logo',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jobs()
    {
        return $this->hasMany(Job::class, 'company_id');
    }

    public function applications()
    {
        return $this->hasManyThrough(Application::class, Job::class, 'company_id', 'job_id');
    }
}


