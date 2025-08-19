<?php

namespace Database\Factories;

use App\Models\UserSkill;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserSkillFactory extends Factory
{
    protected $model = UserSkill::class;

    public function definition(): array
    {
        $skillName = $this->faker->randomElement([
            'JavaScript', 'Python', 'Java', 'PHP', 'React', 'Vue.js', 'Laravel', 'Node.js',
            'MySQL', 'PostgreSQL', 'MongoDB', 'AWS', 'Docker', 'Git', 'Agile', 'Scrum'
        ]);
        
        // Map skills to categories
        $categoryMap = [
            'JavaScript' => 'Programming Languages',
            'Python' => 'Programming Languages',
            'Java' => 'Programming Languages',
            'PHP' => 'Programming Languages',
            'React' => 'Frameworks & Libraries',
            'Vue.js' => 'Frameworks & Libraries',
            'Laravel' => 'Frameworks & Libraries',
            'Node.js' => 'Frameworks & Libraries',
            'MySQL' => 'Databases',
            'PostgreSQL' => 'Databases',
            'MongoDB' => 'Databases',
            'AWS' => 'Cloud & DevOps',
            'Docker' => 'Cloud & DevOps',
            'Git' => 'Tools & Technologies',
            'Agile' => 'Methodologies',
            'Scrum' => 'Methodologies',
        ];
        
        return [
            'user_id' => User::factory(),
            'category' => $categoryMap[$skillName] ?? 'Other',
            'skill_name' => $skillName,
            'proficiency_level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced', 'expert']),
            'years_of_experience' => $this->faker->numberBetween(1, 10),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
