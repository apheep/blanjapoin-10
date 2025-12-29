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
        Schema::table('merchants', function (Blueprint $table) {
            // Menambahkan kolom link_status setelah kolom is_active
            // Default value 1 (aktif) - semua link pelanggan merchant existing tetap dapat diakses
            $table->boolean('link_status')->default(1)->after('is_active')->comment('Status akses link pelanggan: 1=aktif, 0=nonaktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('merchants', function (Blueprint $table) {
            $table->dropColumn('link_status');
        });
    }
};
