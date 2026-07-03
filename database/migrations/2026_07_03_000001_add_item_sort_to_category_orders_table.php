<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category_orders', function (Blueprint $table) {
            // 'none' = tidak diurutkan otomatis, 'redeem_desc' = poin tertinggi dulu, 'redeem_asc' = poin terendah dulu
            $table->string('item_sort')->default('none')->after('is_visible');
        });
    }

    public function down(): void
    {
        Schema::table('category_orders', function (Blueprint $table) {
            $table->dropColumn('item_sort');
        });
    }
};
