<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            } elseif ($driver === 'sqlite') {
                // SQLite 3.25.0+ supports RENAME COLUMN syntax
                // Try RENAME COLUMN first (for SQLite 3.25.0+)
                try {
                    DB::statement('ALTER TABLE keywords RENAME COLUMN rtx TO trx');
                } catch (\Exception $e) {
                    // If RENAME COLUMN fails (SQLite < 3.25.0), use workaround:
                    // Create new column, copy data, drop old column
                    // Note: SQLite doesn't support renaming columns easily, so we keep trx_new
                    // and would need to update application code, or require SQLite 3.25.0+
                    Schema::table('keywords', function (Blueprint $table) {
                        $table->string('trx_new')->nullable()->after('stock');
                    });
                    DB::statement('UPDATE keywords SET trx_new = rtx WHERE rtx IS NOT NULL');
                    Schema::table('keywords', function (Blueprint $table) {
                        $table->dropColumn('rtx');
                    });
                    // For SQLite < 3.25.0, the column will be named trx_new
                    // Application code should handle both trx and trx_new, or require SQLite 3.25.0+
                    // For now, we'll try to rename it (will fail on old SQLite)
                    try {
                        DB::statement('ALTER TABLE keywords RENAME COLUMN trx_new TO trx');
                    } catch (\Exception $e2) {
                        // Column remains as trx_new - this is a limitation of old SQLite versions
                        Log::warning('SQLite version does not support RENAME COLUMN. Column renamed to trx_new. Please use SQLite 3.25.0+ or update application code.');
                    }
                }
            } else {
                // Other databases - try standard RENAME COLUMN
                try {
                    DB::statement('ALTER TABLE keywords RENAME COLUMN rtx TO trx');
                } catch (\Exception $e) {
                    // Fallback: create new column, copy data, drop old
                    Schema::table('keywords', function (Blueprint $table) {
                        $table->string('trx_new')->nullable()->after('stock');
                    });
                    DB::statement('UPDATE keywords SET trx_new = rtx WHERE rtx IS NOT NULL');
                    Schema::table('keywords', function (Blueprint $table) {
                        $table->dropColumn('rtx');
                    });
                    Log::warning('Database does not support RENAME COLUMN. Column renamed to trx_new.');
                }
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
            } elseif ($driver === 'sqlite') {
                // SQLite - use RENAME COLUMN if supported, otherwise skip
                try {
                    DB::statement('ALTER TABLE keywords RENAME COLUMN trx TO rtx');
                } catch (\Exception $e) {
                    // If RENAME COLUMN fails, skip (column might already be renamed)
                }
            } else {
                // Other databases - try standard RENAME COLUMN
                try {
                    DB::statement('ALTER TABLE keywords RENAME COLUMN trx TO rtx');
                } catch (\Exception $e) {
                    // Fallback: skip if rename fails
                }
            }
        }
    }
};
