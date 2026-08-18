<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DummyProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Ensure we have categories and brands
        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
            $categories = Category::all();
        }
        
        // Create a dummy brand if none exists
        if (Brand::count() == 0) {
            Brand::create([
                'name' => 'Generic Brand',
                'slug' => 'generic-brand',
                'description' => 'A generic brand for testing',
                'is_active' => true
            ]);
        }
        $brands = Brand::all();

        // Create 50 dummy products
        for ($i = 0; $i < 50; $i++) {
            $name = $faker->words(3, true) . ' ' . $i;
            $category = $categories->random();
            $brand = $brands->random();
            $price = $faker->numberBetween(50000, 5000000);
            
            Product::create([
                'name' => ucfirst($name),
                'slug' => Str::slug($name),
                'description' => $faker->paragraph,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'price' => $price,
                'stock' => $faker->numberBetween(0, 100),
                'weight' => $faker->numberBetween(100, 2000),
                'is_active' => $faker->boolean(80), // 80% active
                'sold_count' => $faker->numberBetween(0, 500),
                'rating' => $faker->randomFloat(1, 1, 5),
                'discount_percentage' => $faker->boolean(20) ? $faker->numberBetween(5, 50) : 0,
            ]);
        }
    }
}
