<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'session_id',
        'cart_key',
        'quantity',
        'selected_options',
    ];

    protected $casts = [
        'cart_key' => 'integer',
        'quantity' => 'integer',
    ];

    /**
     * Decode selected_options JSON to array (e.g. ['color' => 'Black', 'storage' => '128GB']).
     *
     * @return array<string, string>|null
     */
    public function getSelectedOptionsArrayAttribute(): ?array
    {
        if (empty($this->selected_options)) {
            return null;
        }
        $decoded = json_decode($this->selected_options, true);
        if (! is_array($decoded)) {
            return null;
        }
        return array_filter($decoded, fn ($v) => $v !== null && (string) $v !== '');
    }
}
