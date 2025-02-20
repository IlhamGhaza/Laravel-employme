<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserPortfolio>
 */
class UserPortfolioFactory extends Factory
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
            'user_id' => \App\Models\User::factory(
                // fn() => random_int(2,7)
            ),
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'project_url' => $this->faker->url(),
            'image_path' => $this->faker->imageUrl(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->optional()->date(),
            'technologies' => $this->faker->words(3, true),
        ];
    }
}
