<?php

namespace Database\Factories;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
       $name = "Giày " . fake()->words(2, true);
        $nameSlug = Str::slug($name);
        return [
            'name' => $name,
            'slug' => $nameSlug,
            'description' => fake()->sentence(),
            // Lấy ảnh thật từ Unsplash
            'image_url' => 'https://loremflickr.com/600/400/shoe,style?random=' . fake()->numberBetween(1, 1000),
            'public_id' => null,
        ];
    }
}
