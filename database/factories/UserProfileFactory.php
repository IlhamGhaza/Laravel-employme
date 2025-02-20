<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
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
            'full_name' => $this->faker->name(),
            'headline' => $this->faker->sentence(),
            'phone' => $this->faker->phoneNumber(),
            'location' => $this->faker->city(),
            'about_me' => $this->faker->paragraph(),
            'skills' => $this->faker->words(5, true),
            'cv_path' => $this->faker->filePath(),
            'linkedin_url' => $this->faker->url(),
            'github_url' => $this->faker->url(),
            'website_url' => $this->faker->url(),
        ];
    }
}
