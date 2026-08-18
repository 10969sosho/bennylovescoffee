<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;

class SimpleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating simple sample data...');

        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@bennylovescoffee.com'],
            [
                'name' => 'Admin Bennylovescoffee',
                'email' => 'admin@bennylovescoffee.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'email_verified_at' => now(),
            ]
        );

        // Create Regular User
        User::updateOrCreate(
            ['email' => 'john@example.com'],
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'client',
                'email_verified_at' => now(),
            ]
        );

        // Create Categories
        $categories = [
            [
                'name' => 'Coffee Grinders',
                'slug' => 'coffee-grinders',
                'description' => 'Manual and electric coffee grinders for perfect grind consistency',
                'is_active' => true,
            ],
            [
                'name' => 'Brewing Equipment',
                'slug' => 'brewing-equipment',
                'description' => 'French press, pour-over, and other brewing equipment',
                'is_active' => true,
            ],
            [
                'name' => 'Coffee Beans',
                'slug' => 'coffee-beans',
                'description' => 'Premium coffee beans from around the world',
                'is_active' => true,
            ],
            [
                'name' => 'Espresso Machines',
                'slug' => 'espresso-machines',
                'description' => 'Professional and home espresso machines',
                'is_active' => true,
            ],
            [
                'name' => 'Accessories',
                'slug' => 'accessories',
                'description' => 'Coffee accessories and tools',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $categoryData) {
            Category::updateOrCreate(
                ['slug' => $categoryData['slug']],
                $categoryData
            );
        }

        // Create Products
        $grindersCategory = Category::where('slug', 'coffee-grinders')->first();
        $brewingCategory = Category::where('slug', 'brewing-equipment')->first();
        $beansCategory = Category::where('slug', 'coffee-beans')->first();

        $products = [
            [
                'name' => 'bennylovescoffe C3 Grinder',
                'slug' => 'timemore-c3-grinder',
                'description' => 'Premium manual coffee grinder with 38mm steel burrs. Perfect for espresso and filter coffee brewing.',
                'price' => 1200000,
                'stock' => 50,
                'category_id' => $grindersCategory->id,
                'image' => 'products/timemore-c3-main.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Hario Skerton Pro',
                'slug' => 'hario-skerton-pro',
                'description' => 'Classic manual coffee grinder with ceramic burrs. Great for beginners.',
                'price' => 599000,
                'stock' => 30,
                'category_id' => $grindersCategory->id,
                'image' => 'products/hario-skerton-main.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Bodum French Press',
                'slug' => 'bodum-french-press',
                'description' => 'Classic French press for rich, full-bodied coffee.',
                'price' => 450000,
                'stock' => 25,
                'category_id' => $brewingCategory->id,
                'image' => 'products/bodum-french-press-main.jpg',
                'is_active' => true,
            ],
            [
                'name' => 'Ethiopian Coffee Beans',
                'slug' => 'ethiopian-coffee-beans',
                'description' => 'Premium Ethiopian single-origin coffee beans with floral notes.',
                'price' => 150000,
                'stock' => 100,
                'category_id' => $beansCategory->id,
                'image' => 'products/ethiopian-beans-main.jpg',
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
        }

        // Create Banners
        $banners = [
            [
                'title' => 'bennylovescoffe C3 Grinder - Premium Quality',
                'description' => 'Premium manual coffee grinder with 38mm steel burrs. Perfect for espresso and filter coffee brewing.',
                'image' => 'banners/timemore-c3-banner.jpg',
                'position' => 1,
                'is_active' => true,
                'link_url' => '/products/timemore-c3-grinder',
                'link_text' => 'Shop Now',
            ],
            [
                'title' => 'Coffee Grinders Collection',
                'description' => 'Discover our premium collection of manual and electric coffee grinders for the perfect grind consistency.',
                'image' => 'banners/grinders-collection-banner.jpg',
                'position' => 2,
                'is_active' => true,
                'link_url' => '/categories/coffee-grinders',
                'link_text' => 'Explore Collection',
            ],
            [
                'title' => 'Brewing Equipment Sale',
                'description' => 'Up to 30% off on all brewing equipment. French press, pour-over, and more!',
                'image' => 'banners/brewing-sale-banner.jpg',
                'position' => 3,
                'is_active' => true,
                'link_url' => '/categories/brewing-equipment',
                'link_text' => 'Shop Sale',
            ],
        ];

        foreach ($banners as $bannerData) {
            Banner::updateOrCreate(
                ['position' => $bannerData['position']],
                $bannerData
            );
        }

        // Create Flash Sale
        $flashSale = FlashSale::updateOrCreate(
            ['title' => 'Coffee Grinders Flash Sale'],
            [
                'title' => 'Coffee Grinders Flash Sale',
                'description' => 'Limited time offer on premium coffee grinders. Get up to 33% off on selected models!',
                'start_time' => now(),
                'end_time' => now()->addDays(3),
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        // Create Flash Sale Items
        $timemoreProduct = Product::where('slug', 'timemore-c3-grinder')->first();
        $harioProduct = Product::where('slug', 'hario-skerton-pro')->first();

        $flashSaleItems = [
            [
                'flash_sale_id' => $flashSale->id,
                'product_id' => $timemoreProduct->id,
                'flash_price' => 800000, // 33% discount
                'flash_stock' => 20,
                'sold_count' => 5,
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'flash_sale_id' => $flashSale->id,
                'product_id' => $harioProduct->id,
                'flash_price' => 450000, // 25% discount
                'flash_stock' => 15,
                'sold_count' => 8,
                'sort_order' => 2,
                'is_active' => true,
            ],
        ];

        foreach ($flashSaleItems as $itemData) {
            FlashSaleItem::updateOrCreate(
                [
                    'flash_sale_id' => $itemData['flash_sale_id'],
                    'product_id' => $itemData['product_id']
                ],
                $itemData
            );
        }

        // Create Articles
        $articles = [
            [
                'title' => 'The Art of Coffee Grinding',
                'slug' => 'art-of-coffee-grinding',
                'content' => 'Learn the importance of proper coffee grinding and how it affects your brew quality.',
                'excerpt' => 'Discover the secrets to perfect coffee grinding for the best cup of coffee.',
                'featured_image' => 'articles/coffee_article_1.jpg',
                'author' => 'Admin Bennylovescoffee',
                'published_date' => now()->format('Y-m-d'),
                'read_time' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Brewing Methods Guide',
                'slug' => 'brewing-methods-guide',
                'content' => 'A comprehensive guide to different coffee brewing methods and their characteristics.',
                'excerpt' => 'Explore various coffee brewing techniques to find your perfect cup.',
                'featured_image' => 'articles/brewing-methods.jpg',
                'author' => 'Admin Bennylovescoffee',
                'published_date' => now()->format('Y-m-d'),
                'read_time' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($articles as $articleData) {
            Article::updateOrCreate(
                ['slug' => $articleData['slug']],
                $articleData
            );
        }


        $this->command->info('Simple sample data created successfully!');
        $this->command->info('Admin: admin@bennylovescoffee.com / password');
        $this->command->info('User: john@example.com / password');
        $this->command->info('Categories: ' . Category::count());
        $this->command->info('Products: ' . Product::count());
        $this->command->info('Banners: ' . Banner::count());
        $this->command->info('Flash Sales: ' . FlashSale::count());
        $this->command->info('Articles: ' . Article::count());
    }
}
