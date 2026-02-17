<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->unsignedInteger('quantity')->default(0);
            $table->timestamps();

            $table->unique('variant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
