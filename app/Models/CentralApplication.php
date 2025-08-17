<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentralApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'central_job_id',
        'tenant_id',
        'external_job_id',
        'candidate_id',      // optional user id in portal, if logged in
        'candidate_email',
        'candidate_name',
        'candidate_phone',
        'resume_url',
        'cover_letter',
        'payload',           // raw JSON payload forwarded to tenant
        'status',            // local tracking if needed
        'tenant_response',   // JSON from tenant API
    ];

    protected $casts = [
        'payload' => 'array',
        'tenant_response' => 'array',
    ];
}


