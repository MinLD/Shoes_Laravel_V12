<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
   {
        $name = "Giày " . fake()->company() . " " . fake()->word();
        return [
            'category_id' => Category::factory(), // Tự gán 1 category
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(3),
            'status' => fake()->randomElement(['draft', 'published']), // Trạng thái ngẫu nhiên
        ];
    }
}
