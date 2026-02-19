<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->longText('description')->nullable()->after('is_active');
            $table->longText('payment_info')->nullable()->after('description');
            $table->longText('shipping_info')->nullable()->after('payment_info');
            $table->longText('returns_info')->nullable()->after('shipping_info');
            $table->longText('warranty_info')->nullable()->after('returns_info');
            $table->longText('other_policies')->nullable()->after('warranty_info');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->string('color')->nullable()->after('variant_name');
            $table->string('storage')->nullable()->after('color');
            $table->string('condition')->nullable()->after('storage');
        });

        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->string('alt')->nullable();
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('recently_viewed', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 120)->index();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamp('viewed_at');
            $table->timestamps();

            $table->index(['session_id', 'viewed_at']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'description', 'payment_info', 'shipping_info',
                'returns_info', 'warranty_info', 'other_policies',
            ]);
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropColumn(['color', 'storage', 'condition']);
        });

        Schema::dropIfExists('recently_viewed');
        Schema::dropIfExists('product_images');
    }
};
