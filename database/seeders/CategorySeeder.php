<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Hand Grinder',
                'slug' => 'hand-grinder',
                'description' => 'Manual coffee grinders for precise grinding control',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Electric Grinder',
                'slug' => 'electric-grinder',
                'description' => 'Electric coffee grinders for convenience and consistency',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Scale',
                'slug' => 'scale',
                'description' => 'Precision scales for accurate coffee measurements',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Kettle',
                'slug' => 'kettle',
                'description' => 'Electric kettles for precise temperature control',
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
