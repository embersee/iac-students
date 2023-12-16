<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'telegram-id',
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'username' => fake()->userName(),
            'photo_url' => fake()->imageUrl(),
            'auth_date' => fake()->unixTime(),
            'hash' => fake()->sha256()
        ];
    }

}
