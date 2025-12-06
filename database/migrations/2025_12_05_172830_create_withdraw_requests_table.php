<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('withdraw_requests', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // Foreign key ke merchants.id
            $table->unsignedBigInteger('merchant_id');
            
            // Data penarikan
            $table->string('nama'); // Nama customer (hardcoded "Alexander" untuk sekarang)
            $table->enum('metode_penarikan', ['bca', 'bni', 'bri', 'mandiri', 'linkaja', 'dana']);
            $table->string('no_rekening')->nullable(); // No rekening untuk bank (nullable)
            $table->string('no_ewallet')->nullable(); // No e-wallet untuk e-wallet (nullable, tanpa +62 dan leading 0)
            $table->decimal('jumlah', 15, 2); // Jumlah penarikan
            $table->string('transaction_id')->unique(); // ID transaksi unik
            
            // Status dan approval
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed'])->default('pending');
            $table->unsignedBigInteger('approved_by')->nullable(); // Foreign key ke users.id (admin yang approve)
            $table->timestamp('approved_at')->nullable(); // Waktu approval
            $table->text('dec_reject')->nullable(); // Deskripsi alasan reject (nullable)
            
            $table->timestamps(); // created_at & updated_at
            
            // Foreign keys
            $table->foreign('merchant_id')
                ->references('id')
                ->on('merchants')
                ->onDelete('cascade');
                
            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('withdraw_requests');
    }
};
