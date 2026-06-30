<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $price = $this->faker->randomFloat(2, 10, 500);
        $hasDiscount = $this->faker->boolean(30);

        return [
            'category_id' => Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name),
            'sku' => strtoupper($this->faker->unique()->bothify('SKU-####-????')),
            'description' => $this->faker->paragraph(3),
            'price' => $price,
            'discount_price' => $hasDiscount ? $price * 0.8 : null,
            'stock' => $this->faker->numberBetween(0, 100),
            'featured' => $this->faker->boolean(20),
            'status' => true,
        ];
    }
}
