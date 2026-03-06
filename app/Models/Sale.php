<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = [
        'sale_number', 'customer_name', 'customer_phone', 'customer_email',
        'payment_method', 'subtotal', 'tax_rate', 'tax_amount', 'discount_amount', 'total',
        'notes', 'admin_id',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }

    public static function generateSaleNumber(): string
    {
        $prefix = 'POS';
        $date = now()->format('Ymd');
        $last = static::whereDate('created_at', today())->orderByDesc('id')->first();
        $seq = $last ? (int) substr($last->sale_number, -4) + 1 : 1;
        return $prefix . $date . str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
