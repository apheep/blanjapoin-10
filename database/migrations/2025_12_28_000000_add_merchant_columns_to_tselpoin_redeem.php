<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tokodigi_tselpoin_redeem', function (Blueprint $table) {
            // Tambah kolom merchant_id (referensi ke merchant yang diklik)
            $table->unsignedBigInteger('merchant_id')->nullable()->after('coupon');
            
            // Tambah kolom clicked_date (tanggal klik yang di-match)
            $table->dateTime('clicked_date')->nullable()->after('merchant_id');
            
            // Tambah kolom diff_click (selisih waktu dalam detik antara klik dan redeem)
            $table->integer('diff_click')->nullable()->after('clicked_date');
            
            // Tambah indexes untuk query performance
            $table->index('merchant_id');
            $table->index(['coupon', 'merchant_id']);
            $table->index(['msisdn', 'coupon']);
        });
    }

    public function down(): void
    {
        Schema::table('tokodigi_tselpoin_redeem', function (Blueprint $table) {
            // Drop foreign key jika ada
            $table->dropForeignIdFor(\App\Models\Merchant::class, 'merchant_id');
            
            // Drop indexes
            $table->dropIndex(['merchant_id']);
            $table->dropIndex(['coupon', 'merchant_id']);
            $table->dropIndex(['msisdn', 'coupon']);
            
            // Drop columns
            $table->dropColumn(['merchant_id', 'clicked_date', 'diff_click']);
        });
    }
};
