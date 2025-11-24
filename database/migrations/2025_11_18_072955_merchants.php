<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama_merchant');
            $table->string('kategori')->nullable();
            $table->string('link_blanjapoin')->nullable();
            $table->string('nama_pic')->nullable();
            $table->string('wa_pic')->nullable();
            $table->string('daerah')->nullable();
            $table->string('detail_daerah')->nullable();
            $table->string('long', 50)->nullable(); // Longitude - string untuk mempertahankan nilai asli input
            $table->string('lat', 50)->nullable(); // Latitude - string untuk mempertahankan nilai asli input
            $table->text('link_gmap')->nullable();
            $table->string('logo_merchant')->nullable();
            
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('merchants');
    }
};
