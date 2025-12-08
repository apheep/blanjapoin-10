<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('poin_transaksions', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('package_id', 50)->nullable();
            $table->string('package_type', 150)->nullable();
            $table->string('package_sub_type', 150)->nullable();
            $table->string('package_name', 150)->nullable();
            $table->string('card_type', 50)->nullable();
            $table->bigInteger('msisdn')->nullable();
            $table->integer('price_digipos')->default(0);
            $table->integer('payment_unique')->default(0);
            $table->integer('payment_amount')->default(0);
            $table->string('payment_status', 50)->nullable();
            $table->dateTime('payment_expired')->nullable();
            $table->text('detail')->nullable();
            $table->dateTime('verify_date')->nullable();
            $table->dateTime('created_date')->nullable();
            $table->dateTime('last_update')->nullable();
            $table->string('ref_code', 100)->nullable();
            $table->string('ref_name', 150)->nullable();
            $table->integer('ref_diamond')->default(0);
            $table->tinyInteger('flag')->default(1);
            $table->string('product_id', 50)->nullable();
            $table->string('menu_id', 50)->nullable();
            $table->string('id_digipos', 100)->nullable();
            $table->string('sn_digipos', 100)->nullable();
            $table->string('rrn_payment', 50)->nullable();
            $table->string('device_id', 200)->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->bigInteger('id_group')->nullable();
            $table->bigInteger('agent_id')->nullable();
            $table->string('location_id', 100)->nullable();
            $table->string('location_name', 150)->nullable();
            $table->string('location_type', 100)->nullable();
            $table->string('location_city', 50)->nullable();
            $table->integer('poin_tsel')->default(0);
            $table->char('so_valid', 1)->nullable();
            $table->char('imei_valid', 1)->nullable();

            // Indexes
            $table->index('package_id');
            $table->index('msisdn');
            $table->index('payment_amount');
            $table->index('payment_status');
            $table->index('payment_expired');
            $table->index('flag');
            $table->index('ref_code');
            $table->index('id_digipos');
            $table->index('agent_id');
            $table->index('location_id');
            $table->index('location_city');
            $table->index('id_group');
            $table->index('package_name');
            $table->index('package_type');
            $table->index('package_sub_type');
            $table->index('created_date');
            $table->index('verify_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poin_transaksions');
    }
};
