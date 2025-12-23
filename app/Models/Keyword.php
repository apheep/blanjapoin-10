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

        // Hitung jumlah transaksi redeem dari tokodigi_tselpoin_redeem
        $trxCount = DB::table('tokodigi_tselpoin_redeem')
            ->where('coupon', $this->keyword_id)
            ->where('program', 'BLANJAPOIN')
            ->count();

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
}
