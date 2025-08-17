<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    use HasFactory;

    protected $table = 'user_skills';

    protected $fillable = [
        'user_id',
        'category',
        'skill_name',
        'proficiency_level',
        'years_of_experience',
    ];

    protected $casts = [
        'years_of_experience' => 'integer',
    ];

    /**
     * Get the user that owns the skill.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get skills by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get skills grouped by category.
     */
    public static function getSkillsByCategory($userId)
    {
        return static::where('user_id', $userId)
            ->orderBy('category')
            ->orderBy('skill_name')
            ->get()
            ->groupBy('category');
    }
}
