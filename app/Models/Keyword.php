<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Keyword extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'merchant_key',
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
        'trx',
        'sisa_stock',
        'status',
        'is_active',
        'is_special_promo',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_key', 'id');
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
}
