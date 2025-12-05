<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('iklans', function (Blueprint $table) {
            $table->integer('order')->default(0)->after('id');
        });
        
        // Set order untuk data yang sudah ada
        DB::statement('SET @row_number = 0');
        DB::statement('UPDATE iklans SET `order` = (@row_number:=@row_number+1) ORDER BY created_at DESC');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iklans', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
