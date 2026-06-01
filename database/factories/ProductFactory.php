<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'sku' => 'SKU-' . Str::upper(Str::random(12)),
            'name' => fake()->words(fake()->numberBetween(2, 5), true),
            'price' => fake()->randomFloat(2, 5, 2500),
            'stock_quantity' => fake()->numberBetween(0, 1000),
            'status' => fake()->randomElement(['active', 'active', 'active', 'inactive', 'draft']),
            'created_at' => fake()->dateTimeBetween('-3 years')->format('Y-m-d H:i:s'),
            'updated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }
}
