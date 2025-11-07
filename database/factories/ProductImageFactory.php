<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductImage>
 */
class ProductImageFactory extends Factory
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
        'image_url' => 'https://loremflickr.com/600/400/shoe,' . $colorSlug . '?random=' . fake()->numberBetween(1, 1000),
            'public_id' => null,
        ];
    }
}
