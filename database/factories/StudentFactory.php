<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'date_of_birth' => $this->faker->date('Y-m-d', '2005-12-31'),
            'enrollment_date' => $this->faker->date('Y-m-d', 'now'),
            'student_id' => 'STU' . $this->faker->unique()->numberBetween(1000, 9999),
            'status' => $this->faker->randomElement(['active', 'inactive', 'graduated', 'suspended']),
        ];
    }
}
