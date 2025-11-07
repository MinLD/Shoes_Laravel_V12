<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
   {
        return [
            'user_id' => User::factory(),
            'total_amount' => fake()->numberBetween(500, 10000) * 1000,
            'status' => fake()->randomElement(['pending', 'processing', 'shipped', 'cancelled']),
            'shipping_address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
