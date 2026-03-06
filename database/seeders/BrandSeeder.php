<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Apple', 'sort_order' => 0],
            ['name' => 'Samsung', 'sort_order' => 1],
            ['name' => 'Google', 'sort_order' => 2],
            ['name' => 'Huawei', 'sort_order' => 3],
            ['name' => 'OnePlus', 'sort_order' => 4],
            ['name' => 'Sony', 'sort_order' => 5],
        ];

        foreach ($brands as $b) {
            Brand::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($b['name'])],
                ['name' => $b['name'], 'sort_order' => $b['sort_order']]
            );
        }
    }
}
