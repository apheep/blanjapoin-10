-- ========================================
-- TOKODIGI TSELPOIN REDEEM - MERCHANT DATA POPULATION
-- Database: MySQL 5.7+
-- Created: 2025-12-28
-- ========================================

-- ========================================
-- 1. ALTER TABLE - TAMBAH KOLOM
-- ========================================

ALTER TABLE tokodigi_tselpoin_redeem 
ADD COLUMN merchant_id BIGINT UNSIGNED NULL AFTER coupon,
ADD COLUMN clicked_date DATETIME NULL AFTER merchant_id,
ADD COLUMN diff_click INT NULL AFTER clicked_date;

-- Add indexes untuk performance
ALTER TABLE tokodigi_tselpoin_redeem 
ADD INDEX idx_merchant_id (merchant_id),
ADD INDEX idx_coupon_merchant (coupon, merchant_id),
ADD INDEX idx_msisdn_coupon (msisdn, coupon);


-- ========================================
-- 2. STORED FUNCTION - POPULATE DATA
-- ========================================

DELIMITER $$

CREATE FUNCTION IF NOT EXISTS populate_redeem_merchant_data() 
RETURNS INT DETERMINISTIC READS SQL DATA
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
        LIMIT 10000;
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO v_redeem_id, v_coupon, v_created_date, v_msisdn;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        -- Cari matching click untuk redemption ini
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
END$$

DELIMITER ;


-- ========================================
-- 3. TRIGGER - AUTO UPDATE ON NEW REDEEM
-- ========================================

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS tr_update_redeem_on_create
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
END$$

DELIMITER ;


-- ========================================
-- 4. TRIGGER - UPDATE ON NEW CLICK
-- ========================================

DELIMITER $$

CREATE TRIGGER IF NOT EXISTS tr_update_redemptions_on_new_click
AFTER INSERT ON click_history
FOR EACH ROW
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_redeem_id BIGINT;
    DECLARE v_created_date DATETIME;
    DECLARE v_diff_click INT;
    
    DECLARE cur CURSOR FOR 
        SELECT id, created_date 
        FROM tokodigi_tselpoin_redeem
        WHERE coupon = NEW.keyword_id
        AND program = 'BLANJAPOIN'
        AND created_date > NEW.clicked_at
        AND created_date < DATE_ADD(NEW.clicked_at, INTERVAL 1 HOUR)
        AND (merchant_id IS NULL OR merchant_id != NEW.merchant_id);
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur;
    read_loop: LOOP
        FETCH cur INTO v_redeem_id, v_created_date;
        IF done THEN
            LEAVE read_loop;
        END IF;
        
        SET v_diff_click = TIMESTAMPDIFF(SECOND, NEW.clicked_at, v_created_date);
        
        -- Jika selisih > 3 detik, update jika lebih dekat atau belum ada data
        IF v_diff_click > 3 THEN
            UPDATE tokodigi_tselpoin_redeem
            SET 
                merchant_id = NEW.merchant_id,
                clicked_date = NEW.clicked_at,
                diff_click = v_diff_click
            WHERE id = v_redeem_id
            AND (diff_click IS NULL OR v_diff_click < diff_click);
        END IF;
        
    END LOOP;
    
    CLOSE cur;
END$$

DELIMITER ;


-- ========================================
-- 5. USEFUL QUERIES
-- ========================================

-- Query 1: Hitung Total Redemption per Merchant
SELECT 
    m.id as merchant_id,
    m.nama_merchant,
    COUNT(*) as total_redemptions,
    COUNT(DISTINCT tr.msisdn) as unique_users,
    AVG(tr.diff_click) as avg_click_to_redeem_sec,
    MIN(tr.diff_click) as min_diff_click,
    MAX(tr.diff_click) as max_diff_click
FROM merchants m
LEFT JOIN tokodigi_tselpoin_redeem tr ON m.id = tr.merchant_id AND tr.program = 'BLANJAPOIN'
GROUP BY m.id, m.nama_merchant
ORDER BY total_redemptions DESC;


-- Query 2: Lihat Redemptions yang Belum Ter-Match
SELECT 
    id,
    msisdn,
    coupon,
    created_date,
    merchant_id,
    clicked_date,
    diff_click,
    DATEDIFF(CURDATE(), DATE(created_date)) as days_ago
FROM tokodigi_tselpoin_redeem
WHERE program = 'BLANJAPOIN'
AND (merchant_id IS NULL OR clicked_date IS NULL OR diff_click IS NULL)
ORDER BY created_date DESC
LIMIT 100;


-- Query 3: Distribution Click-to-Redeem Time
SELECT 
    CASE 
        WHEN diff_click BETWEEN 3 AND 10 THEN '3-10 sec'
        WHEN diff_click BETWEEN 11 AND 30 THEN '11-30 sec'
        WHEN diff_click BETWEEN 31 AND 60 THEN '31-60 sec'
        WHEN diff_click BETWEEN 61 AND 300 THEN '1-5 min'
        WHEN diff_click > 300 THEN '> 5 min'
        ELSE 'NULL/NOT_MATCHED'
    END as time_range,
    COUNT(*) as count,
    ROUND(COUNT(*) * 100 / (SELECT COUNT(*) FROM tokodigi_tselpoin_redeem WHERE program = 'BLANJAPOIN'), 2) as percentage
FROM tokodigi_tselpoin_redeem
WHERE program = 'BLANJAPOIN'
GROUP BY time_range
ORDER BY FIELD(time_range, '3-10 sec', '11-30 sec', '31-60 sec', '1-5 min', '> 5 min', 'NULL/NOT_MATCHED');


-- Query 4: Redemptions by Keyword (with Match Rate)
SELECT 
    tr.coupon as keyword_id,
    k.nama_produk,
    COUNT(*) as total_redemptions,
    SUM(CASE WHEN tr.merchant_id IS NOT NULL THEN 1 ELSE 0 END) as matched_redemptions,
    ROUND(SUM(CASE WHEN tr.merchant_id IS NOT NULL THEN 1 ELSE 0 END) * 100 / COUNT(*), 2) as match_percentage,
    COUNT(DISTINCT tr.merchant_id) as unique_merchants
FROM tokodigi_tselpoin_redeem tr
LEFT JOIN keywords k ON tr.coupon = k.keyword_id
WHERE tr.program = 'BLANJAPOIN'
GROUP BY tr.coupon, k.nama_produk
ORDER BY total_redemptions DESC
LIMIT 50;


-- Query 5: Update Keyword.trx dengan Data dari Tokodigi (More Accurate)
UPDATE keywords k
SET k.trx = (
    SELECT COALESCE(COUNT(DISTINCT CONCAT(tr.msisdn, '_', tr.coupon)), 0)
    FROM tokodigi_tselpoin_redeem tr
    WHERE tr.coupon = k.keyword_id
    AND tr.merchant_id = k.merchant_key
    AND tr.program = 'BLANJAPOIN'
)
WHERE k.keyword_id IS NOT NULL;


-- Query 6: Check Data Consistency
SELECT 
    'Total Redemptions' as metric,
    COUNT(*) as value
FROM tokodigi_tselpoin_redeem
WHERE program = 'BLANJAPOIN'
UNION ALL
SELECT 
    'With Merchant Match',
    COUNT(*)
FROM tokodigi_tselpoin_redeem
WHERE program = 'BLANJAPOIN' AND merchant_id IS NOT NULL
UNION ALL
SELECT 
    'Without Merchant Match',
    COUNT(*)
FROM tokodigi_tselpoin_redeem
WHERE program = 'BLANJAPOIN' AND merchant_id IS NULL
UNION ALL
SELECT 
    'Avg Click-to-Redeem (sec)',
    ROUND(AVG(diff_click), 2)
FROM tokodigi_tselpoin_redeem
WHERE program = 'BLANJAPOIN' AND diff_click IS NOT NULL;


-- ========================================
-- 6. VERIFICATION QUERIES
-- ========================================

-- Verify: Kolom sudah ada
DESCRIBE tokodigi_tselpoin_redeem;

-- Verify: Indexes sudah ada
SHOW INDEX FROM tokodigi_tselpoin_redeem WHERE Column_name IN ('merchant_id', 'coupon', 'msisdn');

-- Verify: Function exist
SELECT ROUTINE_NAME, ROUTINE_TYPE FROM INFORMATION_SCHEMA.ROUTINES 
WHERE ROUTINE_SCHEMA = DATABASE() 
AND ROUTINE_NAME = 'populate_redeem_merchant_data';

-- Verify: Triggers exist
SHOW TRIGGERS WHERE `Table` = 'tokodigi_tselpoin_redeem';

-- Verify: Triggers on click_history
SHOW TRIGGERS WHERE `Table` = 'click_history';
