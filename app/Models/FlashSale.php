<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class FlashSale extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_time',
        'end_time',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function items(): HasMany
    {
        return $this->hasMany(FlashSaleItem::class);
    }

    public function activeItems(): HasMany
    {
        return $this->hasMany(FlashSaleItem::class)->where('is_active', true);
    }

    // Helper methods
    public function isActive()
    {
        $now = Carbon::now();
        return $this->is_active && 
               $this->start_time <= $now && 
               $this->end_time >= $now;
    }

    public function isUpcoming()
    {
        return $this->start_time > Carbon::now();
    }

    public function isExpired()
    {
        return $this->end_time < Carbon::now();
    }

    public function getStatus()
    {
        if ($this->isActive()) {
            return 'active';
        } elseif ($this->isUpcoming()) {
            return 'upcoming';
        } else {
            return 'expired';
        }
    }

    public function getTotalItems()
    {
        return $this->items()->count();
    }

    public function getTotalStock()
    {
        return $this->items()->sum('flash_stock');
    }

    public function getTotalSold()
    {
        return $this->items()->sum('sold_count');
    }

    public function getRemainingStock()
    {
        return $this->getTotalStock() - $this->getTotalSold();
    }

    // Scope methods
    public function scopeActive($query)
    {
        $now = Carbon::now();
        return $query->where('is_active', true)
                    ->where('start_time', '<=', $now)
                    ->where('end_time', '>=', $now);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('is_active', true)
                    ->where('start_time', '>', Carbon::now());
    }

    public function scopeExpired($query)
    {
        return $query->where('end_time', '<', Carbon::now());
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')
                    ->orderBy('created_at', 'desc');
    }
}
