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
      Schema::create('keywords', function (Blueprint $table) {
    $table->id();

    // foreign key custom ke merchants.no
    $table->integer('merchant_key');
    $table->foreign('merchant_key')
          ->references('no')->on('merchants')
          ->onDelete('cascade');

    $table->string('nama_produk');
    $table->string('cta_link');
    $table->integer('redeem')->default(0);
    $table->integer('diskon')->nullable();
    $table->string('skb')->nullable();
    $table->date('start_date')->nullable();
    $table->date('end_date')->nullable();
    $table->string('image')->nullable();
    $table->integer('stok')->default(0);

    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
