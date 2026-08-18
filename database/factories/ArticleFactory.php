<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
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
            'content' => $this->faker->paragraphs(5, true),
            'excerpt' => $this->faker->sentence(),
            'author' => $this->faker->name(),
            'is_published' => $this->faker->boolean(70),
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'published_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'read_time' => $this->faker->numberBetween(1, 30), // Added this line
            'views' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
