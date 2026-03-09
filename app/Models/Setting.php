<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $cacheKey = 'setting_' . $key;
        return Cache::remember($cacheKey, 3600, function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            return $row ? $row->value : $default;
        });
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => (string) $value]);
        Cache::forget('setting_' . $key);
    }

    /** Format a numeric amount using the store currency symbol. */
    public static function formatMoney(float $amount, int $decimals = 2): string
    {
        $symbol = static::get('currency', config('currencies.default', '£'));
        return $symbol . number_format((float) $amount, $decimals);
    }
}
