<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashSaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'flash_sale_id',
        'product_id',
        'flash_price',
        'flash_stock',
        'sold_count',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'flash_price' => 'decimal:2',
        'flash_stock' => 'integer',
        'sold_count' => 'integer',
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function flashSale(): BelongsTo
    {
        return $this->belongsTo(FlashSale::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Helper methods
    public function getRemainingStock()
    {
        return $this->flash_stock - $this->sold_count;
    }

    public function getDiscountPercentage()
    {
        if (!$this->product) {
            return 0;
        }
        
        // Prevent division by zero if product price is zero or null
        if (!$this->product->price || $this->product->price <= 0) {
            return 0;
        }
        
        return round((($this->product->price - $this->flash_price) / $this->product->price) * 100);
    }

    public function isAvailable()
    {
        return $this->is_active && 
               $this->getRemainingStock() > 0 &&
               $this->flashSale->isActive();
    }

    public function getStatus()
    {
        if (!$this->is_active) {
            return 'inactive';
        }
        
        if (!$this->flashSale->isActive()) {
            return 'expired';
        }
        
        if ($this->getRemainingStock() <= 0) {
            return 'sold_out';
        }
        
        return 'available';
    }

    // Scope methods
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
                    ->whereRaw('flash_stock > sold_count');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('created_at', 'desc');
    }
}