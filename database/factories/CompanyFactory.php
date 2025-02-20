<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'name' => fake()->company(),
            'logo_path' => fake()->imageUrl(),
            'website' => fake()->url(),
            'description' => fake()->text(),
            'industry' => fake()->word(),
            'location' => fake()->city(),
            'founded_year' => fake()->year(),
        ];
    }
}
