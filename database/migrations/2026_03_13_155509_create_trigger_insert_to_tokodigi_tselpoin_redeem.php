<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Sesuaikan nama kolom NEW.xxx sesuai struktur di tabel tokodigi_app.IGX_TSELPOIN
        // dan sesuaikan juga kolom target di tokodigi_tselpoin_redeem
        
        DB::unprepared('
            CREATE TRIGGER tokodigi_app.tr_igx_tselpoin_after_insert
            AFTER INSERT ON tokodigi_app.IGX_TSELPOIN
            FOR EACH ROW
            BEGIN
                INSERT INTO ' . DB::connection()->getDatabaseName() . '.tokodigi_tselpoin_redeem (
                    program, 
                    coupon,
                    msisdn,
                    keyword_desc,
                    poin_redeem,
                    created_date
                ) VALUES (
                    NEW.program,
                    NEW.coupon,
                    NEW.msisdn,
                    NEW.keyword_desc,
                    NEW.poin_redeem,
                    NEW.created_date
                );
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tokodigi_app.tr_igx_tselpoin_after_insert');
    }
};
