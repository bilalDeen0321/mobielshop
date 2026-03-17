<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductOptionValue extends Model
{
    protected $fillable = ['product_option_definition_id', 'value', 'sort_order'];

    public function definition(): BelongsTo
    {
        return $this->belongsTo(ProductOptionDefinition::class, 'product_option_definition_id');
    }
}
