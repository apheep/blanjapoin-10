<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop trigger if already exists (idempotent)
        DB::unprepared('DROP TRIGGER IF EXISTS tr_update_keyword_stock_on_redeem');

        // Trigger: otomatis update keywords.trx dan keywords.sisa_stock
        // setiap kali ada INSERT baru ke tokodigi_tselpoin_redeem
        // NOTE: tr_update_redeem_on_create (BEFORE INSERT) sudah mengisi merchant_id & clicked_date jika matched
        // Sehingga di AFTER INSERT ini kita bisa membedakan matched vs unmatched
        DB::unprepared("
CREATE TRIGGER tr_update_keyword_stock_on_redeem
AFTER INSERT ON tokodigi_tselpoin_redeem
FOR EACH ROW
BEGIN
    IF NEW.program = 'BLANJAPOIN' THEN
        UPDATE keywords k
        SET
            -- trx hanya dari redeem yang MATCHED ke merchant pemilik keyword ini saja
            k.trx = (
                SELECT COUNT(DISTINCT tr.msisdn)
                FROM tokodigi_tselpoin_redeem tr
                WHERE tr.coupon = NEW.coupon
                  AND tr.program = 'BLANJAPOIN'
                  AND tr.merchant_id IS NOT NULL
                  AND tr.clicked_date IS NOT NULL
                  AND tr.merchant_id = k.merchant_key
            ),
            -- sisa_stock berkurang dari SEMUA redeem (matched maupun tidak)
            k.sisa_stock = GREATEST(
                0,
                k.stock - (
                    SELECT COUNT(DISTINCT msisdn)
                    FROM tokodigi_tselpoin_redeem
                    WHERE coupon = NEW.coupon AND program = 'BLANJAPOIN'
                )
            )
        WHERE k.keyword_id = NEW.coupon;
    END IF;
END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_update_keyword_stock_on_redeem');
    }
};
