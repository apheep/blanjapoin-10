<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Keyword extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Boot method to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        // When a keyword is being deleted (soft or hard), delete associated iklans
        static::deleting(function ($keyword) {
            // Delete all associated iklans
            $keyword->iklans()->delete();
        });
    }

    protected $fillable = [
        'merchant_key',
        'kategori_keyword',
        'nama_produk',
        'keyword_id',
        'cta_link',
        'redeem',
        'diskon',
        'subsidy_amount',
        'diamond_amount',
        'skb',
        'start_date',
        'end_date',
        'image',
        'stock',
        'is_daily_stock',
        'daily_stock_limit',
        'last_stock_reset',
        'trx',
        'sisa_stock',
        'status',
        'is_active',
        'is_special_promo',
        'created_by',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_key', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get all iklans associated with this keyword.
     */
    public function iklans()
    {
        return $this->hasMany(Iklan::class, 'keyword_id', 'id');
    }

    /**
     * Auto-disable keywords that have passed their end_date
     * This method should be called periodically or in controllers
     */
    public static function autoDisableExpiredKeywords()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        // Update keywords where end_date is less than today and is_active is still 1
        $updated = self::whereNotNull('end_date')
            ->where('end_date', '<', $today)
            ->where('is_active', 1)
            ->update(['is_active' => 0]);
        
        return $updated;
    }

    /**
     * Update trx dan sisa_stock untuk keyword ini berdasarkan data dari tokodigi_tselpoin_redeem
     * 
     * Mencegah double counting: 1 MSISDN + 1 keyword_code hanya dihitung 1 kali
     * Hanya merchant dengan time diff terkecil yang menghitung
     * 
     * @return bool
     */
    public function updateTrxAndSisaStock()
    {
        if (!$this->keyword_id) {
            return false;
        }

        // Get all redemptions for this keyword_id (include msisdn untuk prevent double counting)
        // Group by MSISDN + keyword_code, ambil yang pertama (untuk mencegah duplicate entry)
        $redemptions = DB::table('tokodigi_tselpoin_redeem')
            ->where('coupon', $this->keyword_id)
            ->where('program', 'BLANJAPOIN')
            ->select('created_date', 'coupon as keyword_id', 'msisdn')
            ->orderBy('created_date', 'asc') // Urutkan berdasarkan waktu untuk konsistensi
            ->get();

        // Track kombinasi MSISDN + keyword_code yang sudah dihitung untuk merchant ini
        // Ini mencegah double counting: 1 MSISDN + 1 keyword_code hanya dihitung 1 kali
        $countedMsisdnKeyword = [];

        // Count only redemptions that match THIS merchant (via click_history)
        $trxCount = 0;
        foreach ($redemptions as $redemption) {
            // Buat unique key untuk kombinasi MSISDN + keyword_code
            $uniqueKey = $redemption->msisdn . '_' . $redemption->keyword_id;
            
            // Skip jika kombinasi MSISDN + keyword_code ini sudah dihitung sebelumnya
            // Ini mencegah double counting jika ada duplicate entry di database
            if (isset($countedMsisdnKeyword[$uniqueKey])) {
                continue;
            }

            // Find ALL matching clicks for this redemption (dari semua merchant)
            // Ambil yang time diff paling kecil (paling sesuai) - ini menentukan merchant mana yang "menang"
            // Hanya dianggap match jika selisih waktu > 3 detik (karena proses klik, loading mytsel, sampai redeem sukses butuh waktu 3 detik lebih)
            $matchingClick = DB::table('click_history')
                ->where('keyword_id', $redemption->keyword_id)
                ->where('clicked_at', '<', $redemption->created_date)
                ->whereRaw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redemption->created_date}') > 3") // Hanya selisih > 3 detik yang dianggap match
                ->select(
                    'merchant_id',
                    DB::raw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redemption->created_date}') as time_diff_seconds")
                )
                ->orderBy('time_diff_seconds', 'asc') // Paling dekat waktu = paling sesuai
                ->first();

            // Hanya hitung jika:
            // 1. Ada matching click
            // 2. Merchant dari click (yang paling sesuai/time diff terkecil) match dengan merchant keyword ini
            // Ini memastikan 1 MSISDN + keyword_code hanya dihitung untuk 1 merchant (yang paling sesuai)
            // Jika ada 2 merchant dengan keyword sama, hanya yang time diff terkecil yang menghitung
            if ($matchingClick && $matchingClick->merchant_id == $this->merchant_key) {
                $trxCount++;
                // Mark kombinasi MSISDN + keyword_code ini sudah dihitung
                $countedMsisdnKeyword[$uniqueKey] = true;
            }
        }

        // Hitung sisa stock: stock - trx (minimal 0)
        $stock = (int)($this->stock ?? 0);
        $trx = (int)$trxCount;
        $sisaStock = max(0, $stock - $trx);

        // Update ke database
        // trx disimpan sebagai string karena kolom di database adalah string
        $this->trx = (string)$trx;
        $this->sisa_stock = $sisaStock;
        $this->save();

        return true;
    }

    /**
     * Update trx dan sisa_stock untuk semua keyword berdasarkan data dari tokodigi_tselpoin_redeem
     * 
     * @return int Jumlah keyword yang diupdate
     */
    public static function updateAllTrxAndSisaStock()
    {
        $keywords = self::whereNotNull('keyword_id')->get();
        $updatedCount = 0;

        foreach ($keywords as $keyword) {
            if ($keyword->updateTrxAndSisaStock()) {
                $updatedCount++;
            }
        }

        return $updatedCount;
    }

    /**
     * Hitung sisa stock harian untuk daily stock
     * Menghitung: min(daily_stock_limit, sisa_stock) - trx_hari_ini
     * Jika sisa_stock < daily_stock_limit, maka daily stock limit = sisa_stock - trx_hari_ini
     * 
     * @return int Sisa stock harian (daily stock limit yang tersisa hari ini)
     */
    public function getDailyStockRemaining()
    {
        // Cek apakah is_daily_stock aktif (bisa boolean true atau integer 1)
        $isDailyStock = (bool)$this->is_daily_stock;
        if (!$isDailyStock || !$this->daily_stock_limit || !$this->keyword_id) {
            return 0;
        }

        // Ambil sisa_stock saat ini (stock - total_trx)
        $sisaStock = (int)($this->sisa_stock ?? 0);
        
        // Jika sisa_stock sudah 0, maka daily stock limit juga 0
        if ($sisaStock <= 0) {
            return 0;
        }

        // Hitung jumlah trx hari ini untuk keyword ini yang sesuai dengan merchant ini
        // Menggunakan logika yang sama dengan updateTrxAndSisaStock untuk mencegah double counting
        $today = Carbon::today()->format('Y-m-d');
        
        $todayRedemptions = DB::table('tokodigi_tselpoin_redeem')
            ->where('coupon', $this->keyword_id)
            ->where('program', 'BLANJAPOIN')
            ->whereRaw("DATE(created_date) = ?", [$today])
            ->select('created_date', 'coupon as keyword_id', 'msisdn')
            ->orderBy('created_date', 'asc')
            ->get();

        // Track kombinasi MSISDN + keyword_code yang sudah dihitung untuk merchant ini
        $countedMsisdnKeyword = [];
        $todayTrxCount = 0;

        foreach ($todayRedemptions as $redemption) {
            // Buat unique key untuk kombinasi MSISDN + keyword_code
            $uniqueKey = $redemption->msisdn . '_' . $redemption->keyword_id;
            
            // Skip jika kombinasi MSISDN + keyword_code ini sudah dihitung sebelumnya
            if (isset($countedMsisdnKeyword[$uniqueKey])) {
                continue;
            }

            // Find ALL matching clicks for this redemption (dari semua merchant)
            // Ambil yang time diff paling kecil (paling sesuai) - ini menentukan merchant mana yang "menang"
            // Hanya dianggap match jika selisih waktu > 3 detik (karena proses klik, loading mytsel, sampai redeem sukses butuh waktu 3 detik lebih)
            $matchingClick = DB::table('click_history')
                ->where('keyword_id', $redemption->keyword_id)
                ->where('clicked_at', '<', $redemption->created_date)
                ->whereRaw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redemption->created_date}') > 3") // Hanya selisih > 3 detik yang dianggap match
                ->select(
                    'merchant_id',
                    DB::raw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redemption->created_date}') as time_diff_seconds")
                )
                ->orderBy('time_diff_seconds', 'asc') // Paling dekat waktu = paling sesuai
                ->first();

            // Hanya hitung jika:
            // 1. Ada matching click
            // 2. Merchant dari click (yang paling sesuai/time diff terkecil) match dengan merchant keyword ini
            if ($matchingClick && $matchingClick->merchant_id == $this->merchant_key) {
                $todayTrxCount++;
                // Mark kombinasi MSISDN + keyword_code ini sudah dihitung
                $countedMsisdnKeyword[$uniqueKey] = true;
            }
        }

        // Daily stock limit = min(daily_stock_limit, sisa_stock) - trx_hari_ini
        // Jika sisa_stock < daily_stock_limit, maka gunakan sisa_stock sebagai batas
        $dailyStockLimit = min((int)$this->daily_stock_limit, $sisaStock);
        $remaining = max(0, $dailyStockLimit - $todayTrxCount);

        return $remaining;
    }

    /**
     * Get display stock untuk ditampilkan di view
     * Jika daily stock, return daily stock limit, jika tidak return sisa_stock
     * 
     * @return int Stock yang ditampilkan di view
     */
    public function getDisplayStock()
    {
        // Cek is_daily_stock (bisa boolean true atau integer 1)
        $isDailyStock = (bool)($this->is_daily_stock ?? false);
        
        if ($isDailyStock && $this->daily_stock_limit) {
            return $this->getDailyStockRemaining();
        }
        
        return (int)($this->sisa_stock ?? 0);
    }
}
