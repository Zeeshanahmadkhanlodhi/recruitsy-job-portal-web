<?php

namespace Database\Factories;

use App\Models\UserExperience;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserExperienceFactory extends Factory
{
    protected $model = UserExperience::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-5 years', '-1 year');
        $endDate = $this->faker->dateTimeBetween($startDate, 'now');
        
        return [
            'user_id' => User::factory(),
            'company_name' => $this->faker->company(),
            'job_title' => $this->faker->jobTitle(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'is_current' => $this->faker->boolean(20),
            'description' => $this->faker->paragraphs(2, true),
            'achievements' => $this->faker->paragraphs(1, true),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
