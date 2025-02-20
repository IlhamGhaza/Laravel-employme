<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserExperience>
 */
class UserExperienceFactory extends Factory
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
                // fn() => random_int(2, 7)
            ),
            'company_name' => $this->faker->company(),
            'title' => $this->faker->jobTitle(),
            'location' => $this->faker->city(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->optional()->date(),
            'is_current' => $this->faker->boolean(),
            'description' => $this->faker->paragraph(),
        ];
    }
}
