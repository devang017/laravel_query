<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['paid', 'paid', 'paid', 'failed', 'refunded']);

        return [
            'order_id' => Order::factory(),
            'amount' => fake()->randomFloat(2, 15, 4000),
            'payment_method' => fake()->randomElement(['card', 'paypal', 'bank_transfer', 'wallet', 'cod']),
            'status' => $status,
            'paid_at' => $status === 'paid' ? fake()->dateTimeBetween('-3 years')->format('Y-m-d H:i:s') : null,
            'created_at' => fake()->dateTimeBetween('-3 years')->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
