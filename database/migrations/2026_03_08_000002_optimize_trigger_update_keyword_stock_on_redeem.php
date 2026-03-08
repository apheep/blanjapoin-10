<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_update_keyword_stock_on_redeem');

        // Optimized trigger: only fires when NEW.created_date >= keyword.start_date.
        // This correctly handles:
        // - Old data from previous years (created_date < start_date → ignored)
        // - Same msisdn appearing in different programs/periods (not double-counted)
        //
        // - sisa_stock: decremented by 1 when redeem is within keyword's active period
        // - trx: incremented by 1 when redeem is matched (merchant_id set by BEFORE INSERT
        //        trigger) and within keyword's active period
        DB::unprepared("
CREATE TRIGGER tr_update_keyword_stock_on_redeem
AFTER INSERT ON tokodigi_tselpoin_redeem
FOR EACH ROW
BEGIN
    DECLARE v_start_date DATE DEFAULT NULL;

    IF NEW.program = 'BLANJAPOIN' THEN

        -- Fetch the keyword's start_date once
        SELECT start_date INTO v_start_date
        FROM keywords
        WHERE keyword_id = NEW.coupon
        LIMIT 1;

        -- Only process redeems that fall within the keyword's active period.
        -- If start_date IS NULL the keyword has no period restriction → always count.
        IF v_start_date IS NULL OR DATE(NEW.created_date) >= v_start_date THEN

            -- ── sisa_stock ──────────────────────────────────────────────────────
            UPDATE keywords
            SET sisa_stock = GREATEST(0, sisa_stock - 1)
            WHERE keyword_id = NEW.coupon;

            -- ── trx ─────────────────────────────────────────────────────────────
            -- Only matched redeems (BEFORE INSERT trigger fills merchant_id +
            -- clicked_date when a matching click is found)
            IF NEW.merchant_id IS NOT NULL AND NEW.clicked_date IS NOT NULL THEN
                UPDATE keywords
                SET trx = trx + 1
                WHERE keyword_id   = NEW.coupon
                  AND merchant_key = NEW.merchant_id;
            END IF;

        END IF;

    END IF;
END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_update_keyword_stock_on_redeem');

        // Restore original full-recount trigger
        DB::unprepared("
CREATE TRIGGER tr_update_keyword_stock_on_redeem
AFTER INSERT ON tokodigi_tselpoin_redeem
FOR EACH ROW
BEGIN
    IF NEW.program = 'BLANJAPOIN' THEN
        UPDATE keywords k
        SET
            k.trx = (
                SELECT COUNT(DISTINCT tr.msisdn)
                FROM tokodigi_tselpoin_redeem tr
                WHERE tr.coupon = NEW.coupon
                  AND tr.program = 'BLANJAPOIN'
                  AND tr.merchant_id IS NOT NULL
                  AND tr.clicked_date IS NOT NULL
                  AND tr.merchant_id = k.merchant_key
            ),
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
};
