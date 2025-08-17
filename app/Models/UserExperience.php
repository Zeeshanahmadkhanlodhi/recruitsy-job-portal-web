<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserExperience extends Model
{
    use HasFactory;

    protected $table = 'user_experience';

    protected $fillable = [
        'user_id',
        'job_title',
        'company_name',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'achievements',
        'employment_type',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get the user that owns the experience.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get current experience.
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope to get past experience.
     */
    public function scopePast($query)
    {
        return $query->where('is_current', false);
    }

    /**
     * Get the formatted duration.
     */
    public function getFormattedDurationAttribute()
    {
        $start = $this->start_date->format('M Y');
        
        if ($this->is_current) {
            return "{$start} - Present";
        }
        
        if ($this->end_date) {
            $end = $this->end_date->format('M Y');
            return "{$start} - {$end}";
        }
        
        return $start;
    }

    /**
     * Get the total months of experience.
     */
    public function getTotalMonthsAttribute()
    {
        $end = $this->is_current ? now() : $this->end_date;
        return $this->start_date->diffInMonths($end);
    }
}
