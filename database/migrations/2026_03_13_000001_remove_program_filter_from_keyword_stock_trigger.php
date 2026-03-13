<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_update_keyword_stock_on_redeem');

        DB::unprepared("
CREATE TRIGGER tr_update_keyword_stock_on_redeem
AFTER INSERT ON tokodigi_tselpoin_redeem
FOR EACH ROW
BEGIN
    DECLARE v_start_date DATE DEFAULT NULL;

    -- Fetch the keyword's start_date once
    SELECT start_date INTO v_start_date
    FROM keywords
    WHERE keyword_id = NEW.coupon
    LIMIT 1;

    -- Only process redeems that fall within the keyword's active period.
    -- If start_date IS NULL the keyword has no period restriction -> always count.
    IF v_start_date IS NULL OR DATE(NEW.created_date) >= v_start_date THEN

        UPDATE keywords
        SET sisa_stock = GREATEST(0, sisa_stock - 1)
        WHERE keyword_id = NEW.coupon;

        -- Only matched redeems (BEFORE INSERT trigger fills merchant_id +
        -- clicked_date when a matching click is found)
        IF NEW.merchant_id IS NOT NULL AND NEW.clicked_date IS NOT NULL THEN
            UPDATE keywords
            SET trx = trx + 1
            WHERE keyword_id = NEW.coupon
              AND merchant_key = NEW.merchant_id;
        END IF;

    END IF;
END
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS tr_update_keyword_stock_on_redeem');

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
        -- If start_date IS NULL the keyword has no period restriction -> always count.
        IF v_start_date IS NULL OR DATE(NEW.created_date) >= v_start_date THEN

            UPDATE keywords
            SET sisa_stock = GREATEST(0, sisa_stock - 1)
            WHERE keyword_id = NEW.coupon;

            -- Only matched redeems (BEFORE INSERT trigger fills merchant_id +
            -- clicked_date when a matching click is found)
            IF NEW.merchant_id IS NOT NULL AND NEW.clicked_date IS NOT NULL THEN
                UPDATE keywords
                SET trx = trx + 1
                WHERE keyword_id = NEW.coupon
                  AND merchant_key = NEW.merchant_id;
            END IF;

        END IF;

    END IF;
END
        ");
    }
};
