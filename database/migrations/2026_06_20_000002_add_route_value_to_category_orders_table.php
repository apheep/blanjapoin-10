<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('category_orders', function (Blueprint $table) {
            // Drop old unique constraint
            $table->dropUnique(['route_type', 'category_key']);

            // Add route_value: empty string = applies to all paths of that route type
            $table->string('route_value')->default('')->after('route_type');

            // New unique: (route_type, route_value, category_key)
            $table->unique(['route_type', 'route_value', 'category_key']);
        });
    }

    public function down(): void
    {
        Schema::table('category_orders', function (Blueprint $table) {
            $table->dropUnique(['route_type', 'route_value', 'category_key']);
            $table->dropColumn('route_value');
            $table->unique(['route_type', 'category_key']);
        });
    }
};
