<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('keywords', function (Blueprint $table) {
            $table->boolean('is_lock_longlat')->default(true)->after('is_special_promo')
                ->comment('Apakah keyword ini ikut lock longlat radius dari merchant. Default true (ikut lock).');
        });
    }

    public function down()
    {
        Schema::table('keywords', function (Blueprint $table) {
            $table->dropColumn('is_lock_longlat');
        });
    }
};
