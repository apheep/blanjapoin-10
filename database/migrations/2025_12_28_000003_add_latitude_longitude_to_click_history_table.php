<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('click_history', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->comment('User latitude from geolocation');
            $table->decimal('longitude', 11, 8)->nullable()->comment('User longitude from geolocation');
            
            // Add indexes for location-based queries
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::table('click_history', function (Blueprint $table) {
            $table->dropIndex(['latitude', 'longitude']);
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
};
