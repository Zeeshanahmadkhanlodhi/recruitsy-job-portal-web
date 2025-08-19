<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'phone',
        'location',
        'avatar_path',
        'password',
        'date_of_birth',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'bio',
        'is_active',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function savedJobs()
    {
        return $this->hasMany(SavedJob::class);
    }

    /**
     * Get the user's job alerts.
     */
    public function jobAlerts()
    {
        return $this->hasMany(JobAlert::class);
    }

    /**
     * Get the user's professional information.
     */
    public function professionalInfo()
    {
        return $this->hasOne(UserProfessionalInfo::class);
    }

    /**
     * Get the user's skills.
     */
    public function skills()
    {
        return $this->hasMany(UserSkill::class);
    }

    /**
     * Get the user's professional experience.
     */
    public function experience()
    {
        return $this->hasMany(UserExperience::class);
    }

    /**
     * Get the user's education.
     */
    public function education()
    {
        return $this->hasMany(UserEducation::class);
    }

    /**
     * Get the user's resumes.
     */
    public function resumes()
    {
        return $this->hasMany(UserResume::class);
    }

    /**
     * Get the user's primary resume.
     */
    public function primaryResume()
    {
        return $this->hasOne(UserResume::class)->where('is_primary', true);
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        if ($this->first_name && $this->last_name) {
            return "{$this->first_name} {$this->last_name}";
        }
        
        return $this->name;
    }

    /**
     * Get the profile completion percentage.
     */
    public function getProfileCompletionAttribute()
    {
        $fields = [
            'first_name', 'last_name', 'phone', 'location', 'date_of_birth',
            'linkedin_url', 'bio'
        ];
        
        $completed = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $completed++;
            }
        }
        
        // Check related tables
        if ($this->professionalInfo) $completed++;
        if ($this->skills()->count() > 0) $completed++;
        if ($this->experience()->count() > 0) $completed++;
        if ($this->education()->count() > 0) $completed++;
        if ($this->resumes()->count() > 0) $completed++;
        
        $totalFields = count($fields) + 5; // 5 related table checks
        
        return round(($completed / $totalFields) * 100);
    }
}
