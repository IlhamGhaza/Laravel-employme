<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserEducation>
 */
class UserEducationFactory extends Factory
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
            'institution' => $this->faker->company(),
            'degree' => $this->faker->randomElement(['Bachelor', 'Master', 'PhD']),
            'field_of_study' => $this->faker->word(),
            'start_date' => $this->faker->date(),
            'end_date' => $this->faker->optional()->date(),
            'grade' => $this->faker->randomFloat(2, 2, 4),
            'description' => $this->faker->paragraph(),
        ];
    }
}
