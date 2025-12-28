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
        Schema::table('tokodigi_tselpoin_redeem', function (Blueprint $table) {
            // Tambah kolom merchant_id untuk menyimpan merchant dari click yang matching
            $table->unsignedBigInteger('merchant_id')->nullable()->after('keyword_desc');
            
            // Tambah kolom click_date untuk menyimpan waktu klik yang matching
            $table->dateTime('click_date')->nullable()->after('merchant_id');
            
            // Tambah kolom diff_click untuk menyimpan selisih waktu (dalam detik) antara klik dan redeem
            $table->integer('diff_click')->nullable()->after('click_date')->comment('Selisih waktu dalam detik antara click dan redeem');
            
            // Tambah index untuk performa query
            $table->index('merchant_id');
            $table->index('click_date');
            $table->index('diff_click');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tokodigi_tselpoin_redeem', function (Blueprint $table) {
            // Drop indexes first
            $table->dropIndex(['merchant_id']);
            $table->dropIndex(['click_date']);
            $table->dropIndex(['diff_click']);
            
            // Drop columns
            $table->dropColumn(['merchant_id', 'click_date', 'diff_click']);
        });
    }
};
