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
            $table->string('regional')->nullable()->after('territorial');
            $table->string('branch')->nullable()->after('regional');
            $table->string('cluster')->nullable()->after('branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iklans', function (Blueprint $table) {
            $table->dropColumn(['regional', 'branch', 'cluster']);
        });
    }
};
