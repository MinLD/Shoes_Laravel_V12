<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
  {
        $color = fake()->colorName();
        $colorSlug = Str::slug($color);
        return [
            'product_id' => Product::factory(),
            'color' => $color,
            'size' => fake()->randomElement(['39', '40', '41', '42', '43']),
            'price' => fake()->numberBetween(100, 500) * 10000, // 1tr - 5tr
            'stock_quantity' => fake()->numberBetween(0, 100),
            // Lấy ảnh thật theo màu
            'image_url' => 'https://loremflickr.com/600/400/shoe,' . $colorSlug . '?random=' . fake()->numberBetween(1, 1000),
            'public_id' => null,
        ];
    }
}
