<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('click_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('merchant_id');
            $table->string('keyword_id', 50)->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->string('device_id', 200)->nullable();
            $table->dateTime('clicked_at');
            $table->text('user_agent')->nullable();
            $table->string('referer', 500)->nullable();
            $table->timestamps();

            // Indexes for faster queries
            $table->index('merchant_id');
            $table->index('keyword_id');
            $table->index('clicked_at');
            $table->index('device_id');
            $table->index('ip_address');
            
            // Foreign key constraint
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('click_history');
    }
};
