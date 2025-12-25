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
     * @return bool
     */
    public function updateTrxAndSisaStock()
    {
        if (!$this->keyword_id) {
            return false;
        }

        // Get all redemptions for this keyword_id
        $redemptions = DB::table('tokodigi_tselpoin_redeem')
            ->where('coupon', $this->keyword_id)
            ->where('program', 'BLANJAPOIN')
            ->select('created_date', 'coupon as keyword_id')
            ->get();

        // Count only redemptions that match THIS merchant (via click_history)
        $trxCount = 0;
        foreach ($redemptions as $redemption) {
            // Find matching click for this redemption
            $matchingClick = DB::table('click_history')
                ->where('keyword_id', $redemption->keyword_id)
                ->where('clicked_at', '<', $redemption->created_date)
                ->select(
                    'merchant_id',
                    DB::raw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redemption->created_date}') as time_diff_seconds")
                )
                ->orderBy('time_diff_seconds', 'asc')
                ->first();

            // If click found and merchant matches THIS keyword's merchant, count it
            if ($matchingClick && $matchingClick->merchant_id == $this->merchant_key) {
                $trxCount++;
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
     * Menghitung: daily_stock_limit - jumlah redeem hari ini dari tokodigi_tselpoin_redeem
     * 
     * @return int Sisa stock harian
     */
    public function getDailyStockRemaining()
    {
        if (!$this->is_daily_stock || !$this->daily_stock_limit || !$this->keyword_id) {
            return 0;
        }

        // Hitung jumlah redeem hari ini untuk keyword ini
        $todayStart = Carbon::today()->startOfDay();
        $todayEnd = Carbon::today()->endOfDay();

        $todayRedemptions = DB::table('tokodigi_tselpoin_redeem')
            ->where('coupon', $this->keyword_id)
            ->where('program', 'BLANJAPOIN')
            ->whereBetween('created_date', [$todayStart, $todayEnd])
            ->count();

        // Sisa stock = daily_stock_limit - redeem hari ini
        $remaining = max(0, (int)$this->daily_stock_limit - (int)$todayRedemptions);

        return $remaining;
    }
}
