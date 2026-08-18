<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Banner>
 */
class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'image' => 'banners/sample-banner.jpg',
            'link_url' => $this->faker->url(),
            'link_text' => $this->faker->words(2, true),
            'is_active' => $this->faker->boolean(80),
            'position' => $this->faker->numberBetween(1, 10),
        ];
    }
}
