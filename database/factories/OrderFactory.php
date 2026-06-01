<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . fake()->unique()->numerify('##########'),
            'total_amount' => fake()->randomFloat(2, 15, 4000),
            'status' => fake()->randomElement(['pending', 'processing', 'completed', 'completed', 'completed', 'cancelled']),
            'payment_status' => fake()->randomElement(['paid', 'paid', 'paid', 'unpaid', 'refunded']),
            'created_at' => fake()->dateTimeBetween('-3 years')->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
