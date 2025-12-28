<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $table = 'merchants';

    protected $primaryKey = 'id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'daerah',
        'nama_merchant',
        'kategori',
        'logo_merchant',
        'link_blanjapoin',
        'nama_pic',
        'wa_pic',
        'email_pic',
        'ktp_pic',
        'detail_daerah',
        'link_gmap',
        'radius',
        'is_active',
        'start_date',
        'end_date',
        'diamond',
        'created_by',
    ];

    // Jangan cast lat dan long, biarkan sebagai string/decimal dari database
    // Ini mempertahankan format asli yang diinput user

    public function keywords()
    {
        return $this->hasMany(Keyword::class, 'merchant_key', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Calculate total valid transactions for this merchant
     * Based on click history matching (anti-cheating system)
     */
    public function calculateTotalTrx()
    {
        // Get all keywords for this merchant
        $keywords = $this->keywords()->where('is_active', 1)->get();
        
        $totalTrx = 0;
        
        foreach ($keywords as $keyword) {
            if (!$keyword->keyword_id) {
                continue;
            }
            
            // Get all redemptions for this keyword_id
            $redemptions = \DB::table('tokodigi_tselpoin_redeem')
                ->where('coupon', $keyword->keyword_id)
                ->where('program', 'BLANJAPOIN')
                ->get();
            
            foreach ($redemptions as $redemption) {
                // Find the closest click history entry for this redemption
                // Hanya dianggap match jika selisih waktu > 3 detik (karena proses klik, loading mytsel, sampai redeem sukses butuh waktu 3 detik lebih)
                $matchingClick = \DB::table('click_history')
                    ->where('keyword_id', $redemption->coupon)
                    ->where('clicked_at', '<', $redemption->created_date) // Click must be before redeem
                    ->whereRaw("TIMESTAMPDIFF(SECOND, clicked_at, '{$redemption->created_date}') > 3") // Hanya selisih > 3 detik yang dianggap match
                    ->orderByRaw("ABS(TIMESTAMPDIFF(SECOND, clicked_at, '{$redemption->created_date}')) ASC") // Closest time difference
                    ->first();
                
                // If a matching click is found and its merchant_id matches this merchant's id
                if ($matchingClick && $matchingClick->merchant_id == $this->id) {
                    $totalTrx++;
                }
            }
        }
        
        return $totalTrx;
    }

}

