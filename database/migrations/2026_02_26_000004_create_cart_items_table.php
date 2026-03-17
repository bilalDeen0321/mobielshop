<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 191)->index();
            $table->unsignedBigInteger('cart_key'); // variant_id (positive) or -product_id (negative)
            $table->unsignedInteger('quantity')->default(1);
            $table->text('selected_options')->nullable(); // JSON: {"color":"Black","storage":"128GB"}
            $table->timestamps();

            $table->unique(['session_id', 'cart_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
