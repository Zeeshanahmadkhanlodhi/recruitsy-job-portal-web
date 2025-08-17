<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'user_id',
        'candidate_name',
        'candidate_email',
        'candidate_phone',
        'resume_url',
        'cover_letter',
        'status',
        'hr_response',
        'error_message',
    ];

    protected $casts = [
        'hr_response' => 'array',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->hasOneThrough(Company::class, Job::class, 'id', 'id', 'job_id', 'company_id');
    }
}
