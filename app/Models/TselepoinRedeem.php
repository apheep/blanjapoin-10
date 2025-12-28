<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TselepoinRedeem extends Model
{
    use HasFactory;

    protected $table = 'tokodigi_tselpoin_redeem';
    
    public $timestamps = false; // Tabel ini tidak punya updated_at

    protected $fillable = [
        'msisdn',
        'coupon',
        'created_date',
        'merchant_id',
        'clicked_date',
        'diff_click',
        'poin_redeem',
        'program',
        'pin',
        'status',
        'remark',
        'id_trx_header',
        'id_loyalty_txn',
        'id_loyalty_member',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'clicked_date' => 'datetime',
        'diff_click' => 'integer',
        'poin_redeem' => 'integer',
    ];

    // ========================================
    // RELATIONSHIPS
    // ========================================

    /**
     * Relationship ke Merchant (berdasarkan merchant_id yang ter-match dari click)
     */
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    /**
     * Relationship ke Keyword (berdasarkan coupon = keyword_id)
     */
    public function keyword()
    {
        return $this->belongsTo(Keyword::class, 'coupon', 'keyword_id');
    }

    // ========================================
    // SCOPES
    // ========================================

    /**
     * Hanya redemptions BLANJAPOIN
     */
    public function scopeBlanjapoin($query)
    {
        return $query->where('program', 'BLANJAPOIN');
    }

    /**
     * Hanya redemptions yang ter-match dengan click
     */
    public function scopeMatched($query)
    {
        return $query->whereNotNull('merchant_id')
            ->whereNotNull('clicked_date')
            ->whereNotNull('diff_click');
    }

    /**
     * Hanya redemptions yang BELUM ter-match
     */
    public function scopeUnmatched($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('merchant_id')
                ->orWhereNull('clicked_date')
                ->orWhereNull('diff_click');
        });
    }

    /**
     * Filter by merchant_id
     */
    public function scopeForMerchant($query, $merchantId)
    {
        return $query->where('merchant_id', $merchantId);
    }

    /**
     * Filter by keyword/coupon
     */
    public function scopeForKeyword($query, $keywordId)
    {
        return $query->where('coupon', $keywordId);
    }

    /**
     * Filter by date range
     */
    public function scopeBetweenDates($query, $startDate, $endDate = null)
    {
        $endDate = $endDate ?? now();
        return $query->whereBetween('created_date', [$startDate, $endDate]);
    }

    /**
     * Filter redemptions dalam 1 hari terakhir
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_date', Carbon::today());
    }

    /**
     * Filter redemptions dengan click-to-redeem time dalam range tertentu (detik)
     */
    public function scopeWithinClickWindow($query, $minSeconds = 3, $maxSeconds = 3600)
    {
        return $query->matched()
            ->whereBetween('diff_click', [$minSeconds, $maxSeconds]);
    }

    /**
     * Filter hanya matched redemptions dengan minimal time window yang ditentukan
     */
    public function scopeWithClickWithin($query, $seconds)
    {
        return $query->matched()
            ->whereRaw("diff_click <= ?", [$seconds]);
    }

    // ========================================
    // METHODS
    // ========================================

    /**
     * Cek apakah redemption ini sudah ter-match dengan click
     */
    public function isMatched(): bool
    {
        return $this->merchant_id !== null 
            && $this->clicked_date !== null 
            && $this->diff_click !== null;
    }

    /**
     * Hitung durasi click-to-redeem dalam format readable
     * @return string Contoh: "2 minutes 30 seconds"
     */
    public function getClickToRedeemDuration(): string
    {
        if (!$this->diff_click) {
            return 'Not matched';
        }

        $seconds = $this->diff_click;
        $minutes = intdiv($seconds, 60);
        $remainingSeconds = $seconds % 60;

        $parts = [];
        if ($minutes > 0) {
            $parts[] = $minutes . ' minute' . ($minutes > 1 ? 's' : '');
        }
        if ($remainingSeconds > 0) {
            $parts[] = $remainingSeconds . ' second' . ($remainingSeconds > 1 ? 's' : '');
        }

        return implode(' ', $parts) ?: '< 1 second';
    }

    /**
     * Hitung durasi click-to-redeem dalam format readable (short)
     * @return string Contoh: "2m 30s"
     */
    public function getClickToRedeemDurationShort(): string
    {
        if (!$this->diff_click) {
            return '-';
        }

        $seconds = $this->diff_click;
        $minutes = intdiv($seconds, 60);
        $remainingSeconds = $seconds % 60;

        if ($minutes > 0) {
            return "{$minutes}m {$remainingSeconds}s";
        }

        return "{$seconds}s";
    }

    /**
     * Get the merchant name untuk redemption ini
     */
    public function getMerchantName(): ?string
    {
        return $this->merchant?->nama_merchant;
    }

    /**
     * Get the keyword/coupon details
     */
    public function getKeywordDetails(): ?Keyword
    {
        return $this->keyword;
    }

    /**
     * Get match percentage untuk keyword ini
     * (Berapa persen redemption dengan keyword ini yang ter-match)
     */
    public static function getMatchPercentageForKeyword(string $keywordId): float
    {
        $total = self::blanjapoin()->forKeyword($keywordId)->count();
        if ($total === 0) {
            return 0;
        }

        $matched = self::blanjapoin()
            ->forKeyword($keywordId)
            ->matched()
            ->count();

        return round(($matched / $total) * 100, 2);
    }

    /**
     * Get redemption count untuk merchant dalam period tertentu
     */
    public static function getRedemptionCountForMerchant(
        int $merchantId,
        $startDate = null,
        $endDate = null,
        bool $matchedOnly = false
    ): int {
        $query = self::blanjapoin()->forMerchant($merchantId);

        if ($startDate && $endDate) {
            $query->betweenDates($startDate, $endDate);
        }

        if ($matchedOnly) {
            $query->matched();
        }

        return $query->distinct('msisdn')->count('msisdn');
    }

    /**
     * Get avg click-to-redeem time untuk keyword
     */
    public static function getAvgClickToRedeemForKeyword(string $keywordId): ?int
    {
        return self::blanjapoin()
            ->forKeyword($keywordId)
            ->matched()
            ->avg('diff_click');
    }

    /**
     * Get distribution of click-to-redeem times (untuk analytics)
     */
    public static function getClickToRedeemDistribution(): array
    {
        return [
            '3-10 sec' => self::blanjapoin()
                ->matched()
                ->whereBetween('diff_click', [3, 10])
                ->count(),
            '11-30 sec' => self::blanjapoin()
                ->matched()
                ->whereBetween('diff_click', [11, 30])
                ->count(),
            '31-60 sec' => self::blanjapoin()
                ->matched()
                ->whereBetween('diff_click', [31, 60])
                ->count(),
            '1-5 min' => self::blanjapoin()
                ->matched()
                ->whereBetween('diff_click', [61, 300])
                ->count(),
            '> 5 min' => self::blanjapoin()
                ->matched()
                ->where('diff_click', '>', 300)
                ->count(),
        ];
    }
}
