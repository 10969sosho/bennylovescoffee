<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class FeaturedProduct extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'description',
        'sort_order',
        'is_active',
        'featured_at',
        'unfeatured_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'featured_at' => 'datetime',
        'unfeatured_at' => 'datetime',
        'sort_order' => 'integer'
    ];

    /**
     * Relasi ke Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope untuk produk yang aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk produk yang sedang di-featured
     */
    public function scopeCurrentlyFeatured($query)
    {
        $now = Carbon::now();
        return $query->where('featured_products.is_active', true)
                    ->where(function($q) use ($now) {
                        $q->whereNull('featured_products.featured_at')
                          ->orWhere('featured_products.featured_at', '<=', $now);
                    })
                    ->where(function($q) use ($now) {
                        $q->whereNull('featured_products.unfeatured_at')
                          ->orWhere('featured_products.unfeatured_at', '>', $now);
                    });
    }

    /**
     * Scope untuk urutan tampil
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('created_at', 'desc');
    }

    /**
     * Cek apakah produk sedang di-featured
     */
    public function isCurrentlyFeatured(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();

        // Cek featured_at
        if ($this->featured_at && $this->featured_at > $now) {
            return false;
        }

        // Cek unfeatured_at
        if ($this->unfeatured_at && $this->unfeatured_at <= $now) {
            return false;
        }

        return true;
    }

    /**
     * Mulai featured sekarang
     */
    public function startFeatured(): void
    {
        $this->update([
            'is_active' => true,
            'featured_at' => Carbon::now(),
            'unfeatured_at' => null
        ]);
    }

    /**
     * Stop featured sekarang
     */
    public function stopFeatured(): void
    {
        $this->update([
            'is_active' => false,
            'unfeatured_at' => Carbon::now()
        ]);
    }

    /**
     * Set featured untuk periode tertentu
     */
    public function setFeaturedPeriod(Carbon $startDate, ?Carbon $endDate = null): void
    {
        $this->update([
            'is_active' => true,
            'featured_at' => $startDate,
            'unfeatured_at' => $endDate
        ]);
    }
}
