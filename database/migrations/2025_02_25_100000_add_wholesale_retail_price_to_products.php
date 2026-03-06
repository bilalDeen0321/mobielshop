<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('wholesale_price', 10, 2)->default(0)->after('base_price');
            $table->decimal('retail_price', 10, 2)->default(0)->after('wholesale_price');
        });

        DB::table('products')->update([
            'retail_price' => DB::raw('base_price'),
        ]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['wholesale_price', 'retail_price']);
        });
    }
};
