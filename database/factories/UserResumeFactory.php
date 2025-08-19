<?php

namespace Database\Factories;

use App\Models\UserResume;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserResumeFactory extends Factory
{
    protected $model = UserResume::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->randomElement(['Professional Resume', 'Technical Resume', 'Creative Resume', 'Executive Resume']),
            'file_path' => 'resumes/' . $this->faker->uuid() . '.pdf',
            'file_name' => $this->faker->randomElement(['resume.pdf', 'cv.pdf', 'professional_profile.pdf']),
            'file_size' => $this->faker->numberBetween(100000, 5000000), // 100KB to 5MB
            'is_primary' => $this->faker->boolean(80), // 80% chance of being primary
            'description' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
