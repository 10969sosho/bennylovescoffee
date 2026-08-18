<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'discount_price',
        'discount_percentage',
        'image',
        'foto',
        'photos',
        'main_photo',
        'slug',
        'stock',
        'weight',
        'sold_count',
        'rating',
        'is_active',
        'category_id',
        'brand_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'stock' => 'integer',
        'weight' => 'integer',
        'sold_count' => 'integer',
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
        'photos' => 'array',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function featuredProduct()
    {
        return $this->hasOne(FeaturedProduct::class);
    }

    public function isFeatured()
    {
        return $this->featuredProduct && $this->featuredProduct->isCurrentlyFeatured();
    }

    public function flashSaleItems()
    {
        return $this->hasMany(FlashSaleItem::class);
    }

    public function isInFlashSale()
    {
        return $this->flashSaleItems()
                   ->whereHas('flashSale', function($query) {
                       $query->active();
                   })
                   ->where('is_active', true)
                   ->exists();
    }

    public function getCurrentFlashSaleItem()
    {
        return $this->flashSaleItems()
                   ->whereHas('flashSale', function($query) {
                       $query->active();
                   })
                   ->where('is_active', true)
                   ->first();
    }

    // Helper methods
    public function getCurrentPrice()
    {
        // Check if product is in active flash sale
        $flashSaleItem = $this->getCurrentFlashSaleItem();
        if ($flashSaleItem && $flashSaleItem->isAvailable()) {
            return $flashSaleItem->flash_price;
        }
        
        // Regular discount logic
        if ($this->discount_percentage) {
            return $this->price - ($this->price * $this->discount_percentage / 100);
        }
        return $this->discount_price ?? $this->price;
    }

    public function getDiscountPercentage()
    {
        if ($this->discount_percentage) {
            return $this->discount_percentage;
        }
        
        if (!$this->discount_price) {
            return 0;
        }
        
        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    public function calculateDiscountPrice()
    {
        if ($this->discount_percentage) {
            return $this->price - ($this->price * $this->discount_percentage / 100);
        }
        return $this->discount_price;
    }

    public function getDiscountedPrice()
    {
        if ($this->discount_percentage) {
            return $this->price - ($this->price * $this->discount_percentage / 100);
        }
        return $this->discount_price ?? $this->price;
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }

    // Variant-related methods
    public function hasVariants()
    {
        // Use eager loaded data if available, otherwise query database
        if ($this->relationLoaded('variants')) {
            return $this->variants->where('is_active', true)->count() > 0;
        }
        return $this->variants()->where('is_active', true)->exists();
    }

    public function getActiveVariants()
    {
        // Use eager loaded data if available, otherwise query database
        if ($this->relationLoaded('variants')) {
            return $this->variants->where('is_active', true);
        }
        return $this->variants()->where('is_active', true)->get();
    }

    public function getPriceRange()
    {
        if (!$this->hasVariants()) {
            return 'Rp ' . number_format($this->getCurrentPrice(), 0, ',', '.');
        }

        $variants = $this->getActiveVariants();
        if ($variants->isEmpty()) {
            return 'Rp ' . number_format($this->getCurrentPrice(), 0, ',', '.');
        }

        $minPrice = $variants->min('price');
        $maxPrice = $variants->max('price');

        if ($minPrice == $maxPrice) {
            return 'Rp ' . number_format($minPrice, 0, ',', '.');
        }

        return 'Rp ' . number_format($minPrice, 0, ',', '.') . ' - Rp ' . number_format($maxPrice, 0, ',', '.');
    }

    public function getTotalStock()
    {
        if (!$this->hasVariants()) {
            return $this->stock;
        }

        return $this->getActiveVariants()->sum('quantity');
    }

    public function getVariantById($variantId)
    {
        return $this->variants()->where('id', $variantId)->where('is_active', true)->first();
    }
}
