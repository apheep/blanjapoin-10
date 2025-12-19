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
        Schema::table('iklans', function (Blueprint $table) {
            $table->unsignedBigInteger('merchant_key')->nullable()->after('cluster');
            $table->foreign('merchant_key')->references('id')->on('merchants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iklans', function (Blueprint $table) {
            $table->dropForeign(['merchant_key']);
            $table->dropColumn('merchant_key');
        });
    }
};
