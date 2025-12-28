<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('keywords', function (Blueprint $table) {
            $table->boolean('is_daily_stock')->default(false)->after('stock');
            $table->integer('daily_stock_limit')->nullable()->after('is_daily_stock');
            $table->dateTime('last_stock_reset')->nullable()->after('daily_stock_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keywords', function (Blueprint $table) {
            $table->dropColumn(['is_daily_stock', 'daily_stock_limit', 'last_stock_reset']);
        });
    }
};
