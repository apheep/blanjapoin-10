<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop function jika sudah ada
        DB::statement('DROP FUNCTION IF EXISTS populate_redeem_merchant_data');
        
        // Buat stored function untuk populate merchant_id, clicked_date, dan diff_click
        DB::statement(<<<SQL
            CREATE FUNCTION populate_redeem_merchant_data() RETURNS INT DETERMINISTIC
            BEGIN
                DECLARE done INT DEFAULT FALSE;
                DECLARE v_redeem_id BIGINT;
                DECLARE v_coupon VARCHAR(50);
                DECLARE v_created_date DATETIME;
                DECLARE v_msisdn VARCHAR(20);
                DECLARE v_merchant_id BIGINT;
                DECLARE v_clicked_date DATETIME;
                DECLARE v_diff_click INT;
                DECLARE v_updated_count INT DEFAULT 0;
                
                DECLARE cur CURSOR FOR 
                    SELECT id, coupon, created_date, msisdn 
                    FROM tokodigi_tselpoin_redeem 
                    WHERE program = 'BLANJAPOIN' 
                    AND (merchant_id IS NULL OR clicked_date IS NULL OR diff_click IS NULL)
                    LIMIT 10000; -- Process 10000 rows at a time untuk avoid memory issues
                
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
                
                OPEN cur;
                read_loop: LOOP
                    FETCH cur INTO v_redeem_id, v_coupon, v_created_date, v_msisdn;
                    IF done THEN
                        LEAVE read_loop;
                    END IF;
                    
                    -- Cari matching click untuk redemption ini
                    -- Click harus sebelum redeem dan time diff > 3 detik
                    SELECT 
                        merchant_id,
                        clicked_at,
                        TIMESTAMPDIFF(SECOND, clicked_at, v_created_date) as time_diff
                    INTO v_merchant_id, v_clicked_date, v_diff_click
                    FROM click_history
                    WHERE keyword_id = v_coupon
                    AND clicked_at < v_created_date
                    AND TIMESTAMPDIFF(SECOND, clicked_at, v_created_date) > 3
                    ORDER BY TIMESTAMPDIFF(SECOND, clicked_at, v_created_date) ASC
                    LIMIT 1;
                    
                    -- Jika ada matching click, update redemption
                    IF v_merchant_id IS NOT NULL THEN
                        UPDATE tokodigi_tselpoin_redeem 
                        SET 
                            merchant_id = v_merchant_id,
                            clicked_date = v_clicked_date,
                            diff_click = v_diff_click
                        WHERE id = v_redeem_id;
                        
                        SET v_updated_count = v_updated_count + 1;
                    END IF;
                    
                    -- Reset untuk iterasi berikutnya
                    SET v_merchant_id = NULL;
                    SET v_clicked_date = NULL;
                    SET v_diff_click = NULL;
                    
                END LOOP;
                
                CLOSE cur;
                RETURN v_updated_count;
            END
        SQL);
    }

    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS populate_redeem_merchant_data');
    }
};
