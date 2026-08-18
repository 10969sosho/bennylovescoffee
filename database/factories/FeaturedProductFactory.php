<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FeaturedProduct>
 */
class FeaturedProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => \App\Models\Product::factory(),
            'title' => $this->faker->optional(0.3)->sentence(3),
            'description' => $this->faker->optional(0.4)->paragraph(2),
            'sort_order' => $this->faker->numberBetween(0, 100),
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'featured_at' => $this->faker->optional(0.7)->dateTimeBetween('-30 days', 'now'),
            'unfeatured_at' => $this->faker->optional(0.2)->dateTimeBetween('now', '+30 days'),
        ];
    }

    /**
     * Indicate that the featured product is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'featured_at' => now()->subDays(rand(1, 30)),
            'unfeatured_at' => null,
        ]);
    }

    /**
     * Indicate that the featured product is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
            'unfeatured_at' => now()->subDays(rand(1, 10)),
        ]);
    }

    /**
     * Indicate that the featured product has a specific sort order.
     */
    public function sortOrder(int $order): static
    {
        return $this->state(fn (array $attributes) => [
            'sort_order' => $order,
        ]);
    }

    /**
     * Indicate that the featured product is currently featured.
     */
    public function currentlyFeatured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
            'featured_at' => now()->subDays(rand(1, 7)),
            'unfeatured_at' => null,
        ]);
    }
}
