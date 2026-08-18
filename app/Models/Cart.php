<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'variant_id',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Helper methods
    public function getTotalPrice()
    {
        if ($this->variant_id) {
            return $this->quantity * $this->variant->price;
        }
        return $this->quantity * $this->product->getCurrentPrice();
    }

    public function getItemName()
    {
        if ($this->variant_id) {
            return $this->product->name . ' - ' . $this->variant->name;
        }
        return $this->product->name;
    }
}
