<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellRequest extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'device_type',
        'brand',
        'model',
        'condition',
        'description',
        'images',
        'status',
    ];

    protected $casts = [
        'images' => 'array',
    ];
}

