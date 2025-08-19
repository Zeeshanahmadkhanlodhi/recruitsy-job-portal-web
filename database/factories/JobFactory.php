<?php

namespace Database\Factories;

use App\Models\Job;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobFactory extends Factory
{
    protected $model = Job::class;

    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'external_id' => $this->faker->unique()->uuid(),
            'title' => $this->faker->jobTitle(),
            'description' => $this->faker->paragraphs(3, true),
            'requirements' => $this->faker->paragraphs(2, true),
            'benefits' => $this->faker->paragraphs(2, true),
            'location' => $this->faker->city() . ', ' . $this->faker->state(),
            'employment_type' => $this->faker->randomElement(['Full-time', 'Part-time', 'Contract', 'Freelance']),
            'salary_min' => $this->faker->numberBetween(30000, 80000),
            'salary_max' => $this->faker->numberBetween(80000, 150000),
            'currency' => 'USD',
            'posted_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
            'apply_url' => $this->faker->url(),
            'is_remote' => $this->faker->boolean(20),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function remote(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_remote' => true,
        ]);
    }

    public function fullTime(): static
    {
        return $this->state(fn (array $attributes) => [
            'employment_type' => 'Full-time',
        ]);
    }

    public function partTime(): static
    {
        return $this->state(fn (array $attributes) => [
            'employment_type' => 'Part-time',
        ]);
    }
}
