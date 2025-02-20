<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Job>
 */
class JobFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => \App\Models\Company::factory(
                // range 1-10
                // fn () => random_int(1, 10)
            ),
            'title' => $this->faker->jobTitle,
            'salary_min' => $this->faker->numberBetween(20000, 50000),
            'salary_max' => $this->faker->numberBetween(60000, 150000),
            'category' => $this->faker->randomElement(['Technology', 'Marketing', 'Sales', 'Finance', 'HR']),
            'location' => $this->faker->city,
            'work_arrangement' => $this->faker->randomElement(['remote', 'hybrid', 'on-site']),
            'job_type' => $this->faker->randomElement(['full-time', 'part-time', 'contract', 'internship', 'freelance']),
            'description' => $this->faker->paragraphs(3, true),
            'requirements' => $this->faker->paragraphs(2, true),
            'responsibilities' => $this->faker->paragraphs(2, true),
            'benefits' => $this->faker->paragraphs(1, true),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}
