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
            $table->unsignedBigInteger('keyword_id')->nullable()->after('merchant_keys');
            $table->foreign('keyword_id')->references('id')->on('keywords')->onDelete('cascade');
            $table->index('keyword_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iklans', function (Blueprint $table) {
            $table->dropForeign(['keyword_id']);
            $table->dropIndex(['keyword_id']);
            $table->dropColumn('keyword_id');
        });
    }
};
