<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Inventory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Mobile Phones', 'slug' => 'mobile-phones'],
            ['name' => 'Tablets', 'slug' => 'tablets'],
            ['name' => 'Headphones', 'slug' => 'headphones'],
            ['name' => 'Accessories', 'slug' => 'accessories'],
        ];

        foreach ($categories as $c) {
            Category::create($c);
        }

        $products = [
            ['name' => 'iPhone 15 Pro Max 256GB', 'slug' => 'iphone-15-pro-max-256gb', 'brand' => 'Apple', 'condition' => 'Refurbished', 'base_price' => 899.99],
            ['name' => 'Samsung Galaxy S24 Ultra 512GB', 'slug' => 'samsung-galaxy-s24-ultra-512gb', 'brand' => 'Samsung', 'condition' => 'Refurbished', 'base_price' => 849.99],
            ['name' => 'Google Pixel 8 Pro 128GB', 'slug' => 'google-pixel-8-pro-128gb', 'brand' => 'Google', 'condition' => 'Refurbished', 'base_price' => 599.99],
            ['name' => 'iPad Air M2 256GB WiFi', 'slug' => 'ipad-air-m2-256gb-wifi', 'brand' => 'Apple', 'condition' => 'New', 'base_price' => 649.99],
        ];

        $cat = Category::first();

        foreach ($products as $p) {
            $product = Product::create([
                'category_id' => $cat->id,
                'name' => $p['name'],
                'slug' => $p['slug'],
                'base_price' => $p['base_price'],
                'brand' => $p['brand'],
                'condition' => $p['condition'],
                'is_active' => true,
            ]);

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => strtoupper(substr(md5($product->slug), 0, 8)),
                'variant_name' => 'Default',
                'price' => $p['base_price'],
            ]);

            Inventory::create([
                'variant_id' => $variant->id,
                'quantity' => 10,
            ]);
        }

        $this->call(ProductDetailSeeder::class);
    }
}
