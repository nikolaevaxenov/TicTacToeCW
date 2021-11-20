<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'login' => $this->faker->userName(),
            'password' => $this->faker->password(),
            'score' => $this->faker->randomNumber(),
            'wins' => $this->faker->randomNumber(),
            'losses' => $this->faker->randomNumber()
        ];
    }
}
