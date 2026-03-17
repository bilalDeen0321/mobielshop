<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_option_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('option_key', 50); // color, storage, size, condition
            $table->string('option_label', 100); // Color, Storage, Size, Condition
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['product_id', 'option_key']);
        });

        Schema::create('product_option_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_option_definition_id')->constrained()->onDelete('cascade');
            $table->string('value', 255);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('size')->nullable()->after('condition');
        });
    }

    public function down(): void
    {
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn('size');
        });
        Schema::dropIfExists('product_option_values');
        Schema::dropIfExists('product_option_definitions');
    }
};
