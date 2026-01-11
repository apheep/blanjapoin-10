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
        Schema::table('portal_users', function (Blueprint $table) {
            // Make email nullable since we're now using wa_pic as primary identifier
            $table->string('email')->nullable()->change();
            
            // Add wa_pic column (WhatsApp PIC number)
            $table->string('wa_pic')->nullable()->unique()->after('email');
            
            // Add merchant_id foreign key
            $table->unsignedBigInteger('merchant_id')->nullable()->after('wa_pic');
            $table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('portal_users', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['merchant_id']);
            
            // Drop columns
            $table->dropColumn(['wa_pic', 'merchant_id']);
            
            // Make email back to NOT NULL
            $table->string('email')->nullable(false)->change();
        });
    }
};
