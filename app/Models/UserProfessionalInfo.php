<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfessionalInfo extends Model
{
    use HasFactory;

    protected $table = 'user_professional_info';

    protected $fillable = [
        'user_id',
        'current_title',
        'years_of_experience',
        'preferred_job_type',
        'willing_to_relocate',
        'expected_salary_min',
        'expected_salary_max',
        'work_authorization',
        'summary',
    ];

    protected $casts = [
        'willing_to_relocate' => 'boolean',
        'years_of_experience' => 'integer',
    ];

    /**
     * Get the user that owns the professional info.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the formatted salary range.
     */
    public function getFormattedSalaryRangeAttribute()
    {
        if ($this->expected_salary_min && $this->expected_salary_max) {
            return "{$this->expected_salary_min} - {$this->expected_salary_max}";
        } elseif ($this->expected_salary_min) {
            return $this->expected_salary_min;
        } elseif ($this->expected_salary_max) {
            return $this->expected_salary_max;
        }
        
        return null;
    }
}
