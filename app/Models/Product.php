<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'base_price', 'wholesale_price', 'retail_price', 'stock_quantity', 'minimum_stock_limit', 'is_on_sale', 'sale_discount_percent', 'brand', 'condition', 'is_active',
        'description', 'payment_info', 'shipping_info', 'returns_info', 'warranty_info', 'other_policies',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'base_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'retail_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'minimum_stock_limit' => 'integer',
        'is_on_sale' => 'boolean',
        'sale_discount_percent' => 'decimal:2',
    ];

    public function getSalePriceAttribute(): ?float
    {
        if (!$this->is_on_sale || $this->sale_discount_percent === null) {
            return null;
        }
        $retail = (float) $this->retail_price;
        return round($retail * (1 - (float) $this->sale_discount_percent / 100), 2);
    }

    public function isLowStock(): bool
    {
        return $this->minimum_stock_limit > 0 && $this->stock_quantity < $this->minimum_stock_limit;
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brandRelation(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function getBrandAttribute($value): ?string
    {
        return $this->brandRelation?->name ?? $value;
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }
}
