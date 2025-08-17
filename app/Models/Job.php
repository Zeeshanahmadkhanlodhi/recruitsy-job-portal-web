<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'job_listings';

    protected $fillable = [
        'company_id',
        'external_id',
        'title',
        'description',
        'requirements',
        'benefits',
        'location',
        'employment_type',
        'salary_min',
        'salary_max',
        'currency',
        'posted_at',
        'apply_url',
        'is_remote',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
        'is_remote' => 'boolean',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}


