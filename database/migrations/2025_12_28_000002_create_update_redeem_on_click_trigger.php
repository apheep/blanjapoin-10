<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Trigger untuk otomatis update tokodigi_tselpoin_redeem ketika ada redeem baru
     * atau click baru yang match dengan redemption yang sudah ada
     */
    public function up(): void
    {
        // Trigger 1: Update redemption ketika created (ketika ada redeem baru)
        // BEFORE INSERT bukan AFTER INSERT karena perlu modify NEW row
        DB::statement('DROP TRIGGER IF EXISTS tr_update_redeem_on_create');
        
        DB::statement(<<<SQL
            CREATE TRIGGER tr_update_redeem_on_create
            BEFORE INSERT ON tokodigi_tselpoin_redeem
            FOR EACH ROW
            BEGIN
                DECLARE v_merchant_id BIGINT;
                DECLARE v_clicked_date DATETIME;
                DECLARE v_diff_click INT;
                
                -- Hanya process jika program = BLANJAPOIN
                IF NEW.program = 'BLANJAPOIN' THEN
                    -- Cari matching click untuk redemption ini
                    SELECT 
                        merchant_id,
                        clicked_at,
                        TIMESTAMPDIFF(SECOND, clicked_at, NEW.created_date) as time_diff
                    INTO v_merchant_id, v_clicked_date, v_diff_click
                    FROM click_history
                    WHERE keyword_id = NEW.coupon
                    AND clicked_at < NEW.created_date
                    AND TIMESTAMPDIFF(SECOND, clicked_at, NEW.created_date) > 3
                    ORDER BY TIMESTAMPDIFF(SECOND, clicked_at, NEW.created_date) ASC
                    LIMIT 1;
                    
                    -- Jika ada matching click, set values
                    IF v_merchant_id IS NOT NULL THEN
                        SET NEW.merchant_id = v_merchant_id;
                        SET NEW.clicked_date = v_clicked_date;
                        SET NEW.diff_click = v_diff_click;
                    END IF;
                END IF;
            END
        SQL);
        
        // Trigger 2: Disabled - table tokodigi_tselpoin_redeem tidak punya kolom 'id'
        // Cukup gunakan trigger ke-1 untuk automatic matching
        DB::statement('DROP TRIGGER IF EXISTS tr_update_redemptions_on_new_click');
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS tr_update_redeem_on_create');
        DB::statement('DROP TRIGGER IF EXISTS tr_update_redemptions_on_new_click');
    }
};
