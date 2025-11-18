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
        Schema::create('merchants', function (Blueprint $table) {
            $table->integer('no')->primary();
            $table->string('daerah', 100);
            $table->string('nama_merchant', 255);
            $table->string('link_kv_google', 2048)->nullable();
            $table->string('kategori', 100)->nullable();
            $table->integer('poin')->nullable();
            $table->string('promo', 255)->nullable();
            $table->timestamps(); // Opsional: tambahkan jika butuh timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};