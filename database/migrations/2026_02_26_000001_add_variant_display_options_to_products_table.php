<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('show_color')->default(true)->after('other_policies');
            $table->boolean('show_storage')->default(true)->after('show_color');
            $table->boolean('show_condition')->default(true)->after('show_storage');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['show_color', 'show_storage', 'show_condition']);
        });
    }
};
