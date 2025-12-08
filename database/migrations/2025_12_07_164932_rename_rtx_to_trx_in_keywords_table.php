<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Rename rtx column to trx if it exists, or ensure trx column exists.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        
        // Check if rtx column exists and trx doesn't exist, then rename
        if (Schema::hasColumn('keywords', 'rtx') && !Schema::hasColumn('keywords', 'trx')) {
            if ($driver === 'mysql') {
                DB::statement('ALTER TABLE keywords CHANGE rtx trx VARCHAR(255) NULL');
            } elseif ($driver === 'pgsql') {
                DB::statement('ALTER TABLE keywords RENAME COLUMN rtx TO trx');
            } else {
                // SQLite or other - use raw SQL
                DB::statement('ALTER TABLE keywords RENAME COLUMN rtx TO trx');
            }
        }
        // If both exist, copy data and drop rtx
        elseif (Schema::hasColumn('keywords', 'rtx') && Schema::hasColumn('keywords', 'trx')) {
            // Copy data from rtx to trx if trx is null
            DB::statement('UPDATE keywords SET trx = rtx WHERE trx IS NULL AND rtx IS NOT NULL');
            // Drop rtx column
            Schema::table('keywords', function (Blueprint $table) {
                $table->dropColumn('rtx');
            });
        }
        // If trx doesn't exist, create it
        elseif (!Schema::hasColumn('keywords', 'trx')) {
            Schema::table('keywords', function (Blueprint $table) {
                $table->string('trx')->nullable()->after('stock');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Only rename back if trx exists and rtx doesn't
        if (Schema::hasColumn('keywords', 'trx') && !Schema::hasColumn('keywords', 'rtx')) {
            $driver = DB::getDriverName();
            
            if ($driver === 'mysql') {
                DB::statement('ALTER TABLE keywords CHANGE trx rtx VARCHAR(255) NULL');
            } elseif ($driver === 'pgsql') {
                DB::statement('ALTER TABLE keywords RENAME COLUMN trx TO rtx');
            } else {
                // SQLite or other
                DB::statement('ALTER TABLE keywords RENAME COLUMN trx TO rtx');
            }
        }
    }
};
