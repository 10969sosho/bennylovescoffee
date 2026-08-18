<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FlashSale>
 */
class FlashSaleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'flash_price' => $this->faker->numberBetween(50000, 500000),
            'flash_stock' => $this->faker->numberBetween(10, 100), // Added this line
            'start_time' => $this->faker->dateTimeBetween('-1 day', 'now'),
            'end_time' => $this->faker->dateTimeBetween('now', '+1 day'),
            'is_active' => $this->faker->boolean(70),
            'sold_count' => $this->faker->numberBetween(0, 100),
        ];
    }
}
