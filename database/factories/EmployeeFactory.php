<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employee_code' => $this->faker->unique()->numerify('EMP###'),
            'name' => $this->faker->name(),
            'position' => $this->faker->jobTitle(),
            'department' => $this->faker->word(),
            'hire_date' => $this->faker->date(),
            'base_salary' => $this->faker->numberBetween(3000, 10000),
        ];
    }
}
