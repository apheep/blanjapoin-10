<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('iklans', function (Blueprint $table) {
            $table->string('apply_scope')->default('specific')->after('keyword_id');
            // 'specific'      = banner hanya untuk lokasi yang dipilih
            // 'all_regional'  = banner diterapkan ke semua link dalam regional ini
            // 'all_branch'    = banner diterapkan ke semua link dalam branch ini
        });
    }

    public function down(): void
    {
        Schema::table('iklans', function (Blueprint $table) {
            $table->dropColumn('apply_scope');
        });
    }
};
