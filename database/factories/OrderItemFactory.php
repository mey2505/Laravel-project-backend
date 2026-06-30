<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_name' => function (array $attributes) {
                return Product::find($attributes['product_id'])->name;
            },
            'unit_price' => function (array $attributes) {
                return Product::find($attributes['product_id'])->price;
            },
            'quantity' => $this->faker->numberBetween(1, 5),
            'total' => function (array $attributes) {
                return $attributes['unit_price'] * $attributes['quantity'];
            },
        ];
    }
}
