<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keywords', function (Blueprint $table) {
            $table->bigIncrements('id');

            // foreign key ke merchants.id
            $table->unsignedBigInteger('merchant_key');

            $table->string('nama_produk');
            $table->string('cta_link')->nullable();
            $table->string('redeem')->nullable();
            $table->string('diskon')->nullable();       // bisa diganti decimal kalau mau
            $table->text('skb')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('image')->nullable();
            $table->integer('stock')->default(0);

            $table->timestamps(); // created_at & updated_at

            $table->foreign('merchant_key')
                ->references('id')
                ->on('merchants')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keywords');
    }
};
