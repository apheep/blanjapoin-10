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
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_level')->nullable()->after('can_approve');
            $table->string('area')->nullable()->after('user_level');
            $table->string('regional')->nullable()->after('area');
            $table->string('branch')->nullable()->after('regional');
            $table->string('sub_branch')->nullable()->after('branch');
            $table->string('cluster')->nullable()->after('sub_branch');
            $table->string('city')->nullable()->after('cluster');
            $table->string('area_level')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'user_level',
                'area',
                'regional',
                'branch',
                'sub_branch',
                'cluster',
                'city',
                'area_level'
            ]);
        });
    }
};
