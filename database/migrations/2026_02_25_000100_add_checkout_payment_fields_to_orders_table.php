<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('card_name')->nullable()->after('payment_status');
            $table->string('card_last_four', 4)->nullable()->after('card_name');
            $table->string('card_expiry_month', 2)->nullable()->after('card_last_four');
            $table->string('card_expiry_year', 4)->nullable()->after('card_expiry_month');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'card_name',
                'card_last_four',
                'card_expiry_month',
                'card_expiry_year',
            ]);
        });
    }
};
