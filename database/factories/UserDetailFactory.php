<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserDetail>
 */
class UserDetailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'last_name' => fake()->name(),
            'first_name' => fake()->name(),
            'patr_name' => fake()->name(),
            'birth_date' => fake()->date(),
            'gender' => fake()->randomElement(['m', 'w'])
        ];
    }

    public function unverified()
    {

    }
}
