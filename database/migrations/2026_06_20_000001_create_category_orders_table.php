<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('category_orders', function (Blueprint $table) {
            $table->id();
            $table->string('route_type'); // default, u, reg, poin-tsel, cluster, city
            $table->string('category_key'); // belanja, kuliner, telkomsel, etc.
            $table->integer('order_index')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->timestamps();

            $table->unique(['route_type', 'category_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_orders');
    }
};
