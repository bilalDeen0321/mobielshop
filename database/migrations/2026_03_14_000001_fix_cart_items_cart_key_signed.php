<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // cart_key stores variant_id (positive) or -product_id (negative); must be signed
        DB::statement('ALTER TABLE cart_items MODIFY cart_key BIGINT NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE cart_items MODIFY cart_key BIGINT UNSIGNED NOT NULL');
    }
};
