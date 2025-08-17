<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'hr_portal_url' => $this->faker->url(),
            'api_key' => $this->faker->uuid(),
            'api_secret' => $this->faker->sha1(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withoutApiCredentials(): static
    {
        return $this->state(fn (array $attributes) => [
            'api_key' => null,
            'api_secret' => null,
        ]);
    }

    public function withoutPortalUrl(): static
    {
        return $this->state(fn (array $attributes) => [
            'hr_portal_url' => null,
        ]);
    }
}
