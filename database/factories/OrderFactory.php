<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 50, 1000);
        $tax = $subtotal * 0.1;
        $shipping = 10;

        return [
            'user_id' => User::factory(),
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping_fee' => $shipping,
            'total' => $subtotal + $tax + $shipping,
            'status' => $this->faker->randomElement(['pending', 'processing', 'completed', 'cancelled']),
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'failed']),
            'payment_method' => $this->faker->randomElement(['credit_card', 'paypal', 'bank_transfer']),
            'shipping_address' => $this->faker->address(),
        ];
    }
}
