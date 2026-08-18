<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\ProductVariant;

class TimemoreProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create or get brands
        $timemoreBrand = Brand::firstOrCreate(
            ['name' => 'Timemore'],
            ['slug' => 'timemore', 'description' => 'Premium coffee equipment manufacturer']
        );
        
        $oneZpressoBrand = Brand::firstOrCreate(
            ['name' => '1Zpresso'],
            ['slug' => '1zpresso', 'description' => 'Professional hand grinder manufacturer']
        );
        
        $kinuBrand = Brand::firstOrCreate(
            ['name' => 'Kinu'],
            ['slug' => 'kinu', 'description' => 'German precision grinder manufacturer']
        );
        
        $kingrinderBrand = Brand::firstOrCreate(
            ['name' => 'Kingrinder'],
            ['slug' => 'kingrinder', 'description' => 'Affordable coffee grinder manufacturer']
        );
        
        $mischiefBrand = Brand::firstOrCreate(
            ['name' => 'Mischief'],
            ['slug' => 'mischief', 'description' => 'Professional coffee equipment manufacturer']
        );
        
        $millabBrand = Brand::firstOrCreate(
            ['name' => 'Millab'],
            ['slug' => 'millab', 'description' => 'Laboratory coffee equipment manufacturer']
        );

        // Get categories
        $handGrinderCategory = Category::where('slug', 'hand-grinder')->first();
        $electricGrinderCategory = Category::where('slug', 'electric-grinder')->first();
        $scaleCategory = Category::where('slug', 'scale')->first();
        $kettleCategory = Category::where('slug', 'kettle')->first();

        $products = [
            // Hand Grinders
            [
                'name' => 'Timemore C5',
                'slug' => 'timemore-c5',
                'description' => 'Burr Generasi Terbaru

Sekarang menggunakan generasi burr S2C-042-III yang memberikan efek ke hasil gilingan yang lebih konsisten dan result taste yang lebih baik dari generasi sebelumnya.

Kapasitas Lebih Besar
Catch cup baru berkapasitas 25g, pas buat kamu yang suka seduh kopi dalam jumlah lebih banyak.

Desain yang Makin Keren
Sekarang hadir dalam dua warna baru: Etched Black dengan pola ukiran yang bikin lebih mantap digenggam, dan Matte White dengan tampilan tekstur halus seperti batu mineral — elegan tapi tetap minimalis.',
                'price' => 815000,
                'category_id' => $handGrinderCategory->id,
                'brand_id' => $timemoreBrand->id,
                'stock' => 0, // Will be managed by variants
                'is_active' => true,
                'image' => 'products/timemore-c5.jpg',
                'variants' => [
                    ['name' => 'Black', 'price' => 815000, 'quantity' => 25],
                    ['name' => 'White', 'price' => 815000, 'quantity' => 25],
                ]
            ],
            [
                'name' => 'Timemore C5 Esp',
                'slug' => 'timemore-c5-esp',
                'description' => 'Burr Generasi Terbaru

Sekarang menggunakan generasi burr S2C-042-III yang memberikan efek ke hasil gilingan yang lebih konsisten dan result taste yang lebih baik dari generasi sebelumnya.

Kapasitas Lebih Besar
Catch cup baru berkapasitas 25g, pas buat kamu yang suka seduh kopi dalam jumlah lebih banyak.

Desain yang Makin Keren
Sekarang hadir dalam dua warna baru: Etched Black dengan pola ukiran yang bikin lebih mantap digenggam, dan Matte White dengan tampilan tekstur halus seperti batu mineral — elegan tapi tetap minimalis.

Dial for Espresso
Hadir dengan dial set yang lebih advance. Dial set ini berguna buat kamu yang senang ngulik mencari grind size yang tepat untuk espresso',
                'price' => 905000,
                'category_id' => $handGrinderCategory->id,
                'brand_id' => $timemoreBrand->id,
                'stock' => 0, // Will be managed by variants
                'is_active' => true,
                'image' => 'products/timemore-c5-esp.jpg',
                'variants' => [
                    ['name' => 'Black', 'price' => 905000, 'quantity' => 15],
                    ['name' => 'White', 'price' => 905000, 'quantity' => 15],
                ]
            ],
            [
                'name' => 'Timemore C5 Esp Pro',
                'slug' => 'timemore-c5-esp-pro',
                'description' => 'Burr Generasi Terbaru

Sekarang menggunakan generasi burr S2C-042-III yang memberikan efek ke hasil gilingan yang lebih konsisten dan result taste yang lebih baik dari generasi sebelumnya.

Kapasitas Lebih Besar
Catch cup baru berkapasitas 25g, pas buat kamu yang suka seduh kopi dalam jumlah lebih banyak.

Desain yang Makin Keren
Sekarang hadir dalam dua warna baru: Etched Black dengan pola ukiran yang bikin lebih mantap digenggam, dan Matte White dengan tampilan tekstur halus seperti batu mineral — elegan tapi tetap minimalis.

Dial for Espresso
Hadir dengan dial set yang lebih advance. Dial set ini berguna buat kamu yang senang ngulik mencari grind size yang tepat untuk espresso',
                'price' => 995000,
                'category_id' => $handGrinderCategory->id,
                'brand_id' => $timemoreBrand->id,
                'stock' => 20,
                'is_active' => true,
                'image' => 'products/timemore-c5-esp-pro.jpg',
            ],
            [
                'name' => '1Zpresso K Ultra',
                'slug' => '1zpresso-k-ultra',
                'description' => 'Premium hand grinder with titanium-coated burrs for exceptional grind consistency and durability.',
                'price' => 2500000,
                'category_id' => $handGrinderCategory->id,
                'brand_id' => $oneZpressoBrand->id,
                'stock' => 15,
                'is_active' => true,
                'image' => 'products/1zpresso-k-ultra.jpg',
            ],
            [
                'name' => '1Zpresso ZP6',
                'slug' => '1zpresso-zp6',
                'description' => 'Specialized hand grinder designed for pour-over brewing with precise grind control.',
                'price' => 1800000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 20,
                'is_active' => true,
                'image' => 'products/1zpresso-zp6.jpg',
            ],
            [
                'name' => '1Zpresso Q Air',
                'slug' => '1zpresso-q-air',
                'description' => 'Compact and lightweight hand grinder perfect for travel and home use.',
                'price' => 1200000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 30,
                'is_active' => true,
                'image' => 'products/1zpresso-q-air.jpg',
            ],
            [
                'name' => 'Kinu Classic M47',
                'slug' => 'kinu-classic-m47',
                'description' => 'German precision hand grinder with 47mm steel burrs for consistent grinding.',
                'price' => 3500000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 10,
                'is_active' => true,
                'image' => 'products/kinu-classic-m47.jpg',
            ],
            [
                'name' => 'Kinu M47 Traveller',
                'slug' => 'kinu-m47-traveller',
                'description' => 'Portable version of the M47 with travel-friendly design and same quality burrs.',
                'price' => 3200000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 12,
                'is_active' => true,
                'image' => 'products/kinu-m47-traveller.jpg',
            ],
            [
                'name' => 'Kinu M47 Phoenix',
                'slug' => 'kinu-m47-phoenix',
                'description' => 'Premium hand grinder with 47mm steel burrs and precision adjustment.',
                'price' => 3800000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 8,
                'is_active' => true,
                'image' => 'products/kinu-m47-phoenix.jpg',
            ],
            [
                'name' => 'Kinu Titan',
                'slug' => 'kinu-titan',
                'description' => 'Heavy-duty hand grinder with titanium-coated burrs for maximum durability.',
                'price' => 4500000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 5,
                'is_active' => true,
                'image' => 'products/kinu-titan.jpg',
            ],
            [
                'name' => 'Kingrinder K6',
                'slug' => 'kingrinder-k6',
                'description' => 'Affordable hand grinder with 38mm steel burrs for consistent grinding.',
                'price' => 800000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 40,
                'is_active' => true,
                'image' => 'products/kingrinder-k6.jpg',
            ],
            [
                'name' => 'Timemore Chestnut S3',
                'slug' => 'timemore-chestnut-s3',
                'description' => 'Entry-level hand grinder with 38mm steel burrs and simple adjustment.',
                'price' => 450000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 60,
                'is_active' => true,
                'image' => 'products/timemore-chestnut-s3.jpg',
            ],
            [
                'name' => 'Timemore Chestnut S3 Esp',
                'slug' => 'timemore-chestnut-s3-esp',
                'description' => 'Espresso-focused version of the S3 with finer adjustment for espresso grinding.',
                'price' => 550000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 45,
                'is_active' => true,
                'image' => 'products/timemore-chestnut-s3-esp.jpg',
            ],
            [
                'name' => 'Timemore C3',
                'slug' => 'timemore-c3',
                'description' => 'Compact hand grinder with 38mm steel burrs and ergonomic design.',
                'price' => 350000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 80,
                'is_active' => true,
                'image' => 'products/timemore-c3.jpg',
            ],
            [
                'name' => 'Timemore C3 Esp',
                'slug' => 'timemore-c3-esp',
                'description' => 'Espresso version of the C3 with finer grind adjustment for espresso.',
                'price' => 400000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 70,
                'is_active' => true,
                'image' => 'products/timemore-c3-esp.jpg',
            ],
            [
                'name' => 'Timemore C3 Esp Pro',
                'slug' => 'timemore-c3-esp-pro',
                'description' => 'Professional version of the C3 Esp with enhanced features and build quality.',
                'price' => 500000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 35,
                'is_active' => true,
                'image' => 'products/timemore-c3-esp-pro.jpg',
            ],
            [
                'name' => 'Timemore Slim 3',
                'slug' => 'timemore-slim-3',
                'description' => 'Ultra-compact hand grinder perfect for travel and minimal space.',
                'price' => 300000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 50,
                'is_active' => true,
                'image' => 'products/timemore-slim-3.jpg',
            ],
            [
                'name' => 'Timemore C3S',
                'slug' => 'timemore-c3s',
                'description' => 'Updated version of the C3 with improved burrs and adjustment mechanism.',
                'price' => 380000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 55,
                'is_active' => true,
                'image' => 'products/timemore-c3s.jpg',
            ],
            [
                'name' => 'Timemore Nano 3',
                'slug' => 'timemore-nano-3',
                'description' => 'Nano-sized hand grinder with precision grinding in a compact package.',
                'price' => 320000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 40,
                'is_active' => true,
                'image' => 'products/timemore-nano-3.jpg',
            ],
            [
                'name' => 'Timemore Chestnut X',
                'slug' => 'timemore-chestnut-x',
                'description' => 'Premium hand grinder with 40mm steel burrs and advanced adjustment system.',
                'price' => 750000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 25,
                'is_active' => true,
                'image' => 'products/timemore-chestnut-x.jpg',
            ],
            [
                'name' => 'Timemore Chestnut ESP',
                'slug' => 'timemore-chestnut-esp',
                'description' => 'Espresso-focused hand grinder with precise grind control for espresso brewing.',
                'price' => 650000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 30,
                'is_active' => true,
                'image' => 'products/timemore-chestnut-esp.jpg',
            ],
            [
                'name' => 'Mischief M40',
                'slug' => 'mischief-m40',
                'description' => 'Professional hand grinder with 40mm steel burrs and precision adjustment.',
                'price' => 1500000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 15,
                'is_active' => true,
                'image' => 'products/mischief-m40.jpg',
            ],
            [
                'name' => 'Timemore Millab M01',
                'slug' => 'timemore-millab-m01',
                'description' => 'Laboratory-grade hand grinder for precise grinding and research applications.',
                'price' => 2000000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 8,
                'is_active' => true,
                'image' => 'products/timemore-millab-m01.jpg',
            ],
            [
                'name' => 'Timemore Millab E01',
                'slug' => 'timemore-millab-e01',
                'description' => 'Electric laboratory grinder for consistent and repeatable grinding.',
                'price' => 2500000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 5,
                'is_active' => true,
                'image' => 'products/timemore-millab-e01.jpg',
            ],
            [
                'name' => 'Timemore Whirly 01S',
                'slug' => 'timemore-whirly-01s',
                'description' => 'Compact electric grinder with precise grind control and quiet operation.',
                'price' => 1800000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 12,
                'is_active' => true,
                'image' => 'products/timemore-whirly-01s.jpg',
            ],
            [
                'name' => 'Timemore Nutti OX Lite',
                'slug' => 'timemore-nutti-ox-lite',
                'description' => 'Lightweight hand grinder with ergonomic design and consistent grinding.',
                'price' => 280000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 35,
                'is_active' => true,
                'image' => 'products/timemore-nutti-ox-lite.jpg',
            ],
            [
                'name' => 'Timemore C2S',
                'slug' => 'timemore-c2s',
                'description' => 'Entry-level hand grinder with 38mm steel burrs and simple operation.',
                'price' => 250000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 100,
                'is_active' => true,
                'image' => 'products/timemore-c2s.jpg',
            ],
            [
                'name' => 'Timemore C2',
                'slug' => 'timemore-c2',
                'description' => 'Classic hand grinder with 38mm steel burrs and reliable performance.',
                'price' => 200000,
                'category_id' => $handGrinderCategory->id,
                'stock' => 120,
                'is_active' => true,
                'image' => 'products/timemore-c2.jpg',
            ],

            // Electric Grinders
            [
                'name' => 'Mischief DM40',
                'slug' => 'mischief-dm40',
                'description' => 'Professional electric grinder with 40mm steel burrs and precise grind control.',
                'price' => 3500000,
                'category_id' => $electricGrinderCategory->id,
                'stock' => 8,
                'is_active' => true,
                'image' => 'products/mischief-dm40.jpg',
            ],
            [
                'name' => 'Timemore 078',
                'slug' => 'timemore-078',
                'description' => 'Premium electric grinder with 78mm burrs for exceptional grind consistency.',
                'price' => 8000000,
                'category_id' => $electricGrinderCategory->id,
                'stock' => 3,
                'is_active' => true,
                'image' => 'products/timemore-078.jpg',
            ],
            [
                'name' => 'Timemore 078S',
                'slug' => 'timemore-078s',
                'description' => 'Enhanced version of the 078 with improved features and build quality.',
                'price' => 9500000,
                'category_id' => $electricGrinderCategory->id,
                'stock' => 2,
                'is_active' => true,
                'image' => 'products/timemore-078s.jpg',
            ],
            [
                'name' => 'Timemore 078SSP',
                'slug' => 'timemore-078ssp',
                'description' => 'Top-tier electric grinder with SSP burrs for ultimate grind quality.',
                'price' => 12000000,
                'category_id' => $electricGrinderCategory->id,
                'stock' => 1,
                'is_active' => true,
                'image' => 'products/timemore-078ssp.jpg',
            ],
            [
                'name' => 'Timemore 068',
                'slug' => 'timemore-068',
                'description' => 'Mid-range electric grinder with 68mm burrs for consistent grinding.',
                'price' => 4500000,
                'category_id' => $electricGrinderCategory->id,
                'stock' => 10,
                'is_active' => true,
                'image' => 'products/timemore-068.jpg',
            ],
            [
                'name' => 'Timemore 068S',
                'slug' => 'timemore-068s',
                'description' => 'Enhanced version of the 068 with improved features and performance.',
                'price' => 5500000,
                'category_id' => $electricGrinderCategory->id,
                'stock' => 8,
                'is_active' => true,
                'image' => 'products/timemore-068s.jpg',
            ],
            [
                'name' => 'Timemore 068SSP',
                'slug' => 'timemore-068ssp',
                'description' => 'Premium version with SSP burrs for exceptional grind quality.',
                'price' => 7000000,
                'category_id' => $electricGrinderCategory->id,
                'stock' => 5,
                'is_active' => true,
                'image' => 'products/timemore-068ssp.jpg',
            ],
            [
                'name' => 'T64 Titanium',
                'slug' => 't64-titanium',
                'description' => 'Professional electric grinder with titanium-coated burrs for durability.',
                'price' => 6000000,
                'category_id' => $electricGrinderCategory->id,
                'stock' => 6,
                'is_active' => true,
                'image' => 'products/t64-titanium.jpg',
            ],
            [
                'name' => 'T64 SSP',
                'slug' => 't64-ssp',
                'description' => 'High-end electric grinder with SSP burrs for ultimate grind consistency.',
                'price' => 7500000,
                'category_id' => $electricGrinderCategory->id,
                'stock' => 4,
                'is_active' => true,
                'image' => 'products/t64-ssp.jpg',
            ],
            [
                'name' => 'Mischief Demon Grinder',
                'slug' => 'mischief-demon-grinder',
                'description' => 'Professional electric grinder with advanced features and precise control.',
                'price' => 10000000,
                'category_id' => $electricGrinderCategory->id,
                'stock' => 2,
                'is_active' => true,
                'image' => 'products/mischief-demon-grinder.jpg',
            ],

            // Scales
            [
                'name' => 'Timemore Black Mirror Basic 2 Scale',
                'slug' => 'timemore-black-mirror-basic-2-scale',
                'description' => 'Precision coffee scale with 0.1g accuracy and timer function.',
                'price' => 450000,
                'category_id' => $scaleCategory->id,
                'stock' => 50,
                'is_active' => true,
                'image' => 'products/timemore-black-mirror-basic-2.jpg',
            ],
            [
                'name' => 'Timemore Black Mirror Nano Scale',
                'slug' => 'timemore-black-mirror-nano-scale',
                'description' => 'Compact precision scale with 0.1g accuracy and sleek design.',
                'price' => 350000,
                'category_id' => $scaleCategory->id,
                'stock' => 60,
                'is_active' => true,
                'image' => 'products/timemore-black-mirror-nano.jpg',
            ],
            [
                'name' => 'Timemore Black Mirror Duo Scale',
                'slug' => 'timemore-black-mirror-duo-scale',
                'description' => 'Dual-function scale with separate weighing areas for coffee and water.',
                'price' => 650000,
                'category_id' => $scaleCategory->id,
                'stock' => 30,
                'is_active' => true,
                'image' => 'products/timemore-black-mirror-duo.jpg',
            ],

            // Kettles
            [
                'name' => 'Timemore Fish Smart Electric Kettle 900ml',
                'slug' => 'timemore-fish-smart-electric-kettle-900ml',
                'description' => 'Smart electric kettle with precise temperature control and 900ml capacity.',
                'price' => 1200000,
                'category_id' => $kettleCategory->id,
                'stock' => 25,
                'is_active' => true,
                'image' => 'products/timemore-fish-kettle-900ml.jpg',
            ],
        ];

        foreach ($products as $productData) {
            // Extract variants if they exist
            $variants = $productData['variants'] ?? null;
            unset($productData['variants']);
            
            // Create or update product
            $product = Product::updateOrCreate(
                ['slug' => $productData['slug']],
                $productData
            );
            
            // Create variants if they exist
            if ($variants) {
                foreach ($variants as $variantData) {
                    ProductVariant::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'name' => $variantData['name']
                        ],
                        [
                            'price' => $variantData['price'],
                            'quantity' => $variantData['quantity'],
                            'is_active' => true
                        ]
                    );
                }
            }
        }
    }
}
