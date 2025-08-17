<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserEducation extends Model
{
    use HasFactory;

    protected $table = 'user_education';

    protected $fillable = [
        'user_id',
        'degree',
        'institution',
        'field_of_study',
        'graduation_year',
        'gpa',
        'gpa_scale',
        'description',
        'location',
        'is_current',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'gpa' => 'decimal:2',
        'graduation_year' => 'integer',
        'is_current' => 'boolean',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user that owns the education.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get current education.
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope to get completed education.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_current', false);
    }

    /**
     * Get the formatted GPA.
     */
    public function getFormattedGpaAttribute()
    {
        if ($this->gpa) {
            return "GPA: {$this->gpa}/{$this->gpa_scale}";
        }
        
        return null;
    }

    /**
     * Get the formatted duration.
     */
    public function getFormattedDurationAttribute()
    {
        if ($this->start_date && $this->end_date) {
            $start = $this->start_date->format('Y');
            $end = $this->end_date->format('Y');
            return "{$start} - {$end}";
        } elseif ($this->graduation_year) {
            return $this->graduation_year;
        }
        
        return null;
    }
}
