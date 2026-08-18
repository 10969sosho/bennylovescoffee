<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

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
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->numberBetween(100000, 2000000),
            'discount_percentage' => $this->faker->optional(0.3)->numberBetween(5, 50),
            'stock' => $this->faker->numberBetween(0, 100),
            'sold_count' => $this->faker->numberBetween(0, 500),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'is_active' => $this->faker->boolean(80),
            'category_id' => Category::factory(),
            'slug' => $this->faker->slug(),
        ];
    }
}
