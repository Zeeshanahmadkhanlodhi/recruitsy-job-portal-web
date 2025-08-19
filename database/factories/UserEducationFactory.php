<?php

namespace Database\Factories;

use App\Models\UserEducation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserEducationFactory extends Factory
{
    protected $model = UserEducation::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'institution' => $this->faker->company() . ' University',
            'degree' => $this->faker->randomElement(['Bachelor', 'Master', 'PhD', 'Associate', 'Diploma']),
            'field_of_study' => $this->faker->randomElement([
                'Computer Science', 'Software Engineering', 'Information Technology', 'Data Science',
                'Business Administration', 'Marketing', 'Finance', 'Human Resources'
            ]),
            'graduation_year' => $this->faker->numberBetween(2015, 2025),
            'gpa' => $this->faker->randomFloat(2, 2.5, 4.0),
            'gpa_scale' => '4.0',
            'description' => $this->faker->paragraphs(1, true),
            'location' => $this->faker->city() . ', ' . $this->faker->state(),
            'is_current' => $this->faker->boolean(20),
            'start_date' => $this->faker->dateTimeBetween('-10 years', '-4 years'),
            'end_date' => $this->faker->dateTimeBetween('-4 years', 'now'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
