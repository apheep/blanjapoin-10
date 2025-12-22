<?php

namespace App\Http\Controllers;

use App\Models\Keyword;
use App\Models\Merchant;
use Illuminate\Http\Request;

class SpesialPromoController extends Controller
{
    /**
     * Menampilkan halaman spesial promo untuk public
     * Hanya menampilkan keyword dengan is_special_promo = 1
     */
    public function index()
    {
        // Auto-disable keywords that have passed their end_date
        Keyword::autoDisableExpiredKeywords();
        
        // Query keyword dengan is_special_promo = 1, status approve, dan is_active = 1
        // Pastikan merchant juga aktif jika ada
        $keywords = Keyword::with('merchant')
            ->select('keywords.*')
            ->selectRaw('(SELECT COUNT(*) FROM tokodigi_tselpoin_redeem WHERE coupon = keywords.keyword_id AND program = "BLANJAPOIN") as redeem_count')
            ->where('is_special_promo', 1)
            ->where('is_active', 1)
            ->where('status', 'approve')
            ->whereHas('merchant', function ($query) {
                $query->where('is_active', 1);
            })
            ->orderBy('id', 'desc')
            ->get();
        
        // Set trx dan sisa_stock untuk setiap keyword berdasarkan redeem_count
        foreach ($keywords as $keyword) {
            $keyword->trx = $keyword->redeem_count ?? 0;
            // Hitung sisa stock: stock - trx (minimal 0)
            $stock = (int)($keyword->stock ?? 0);
            $trx = (int)($keyword->trx ?? 0);
            $keyword->sisa_stock = max(0, $stock - $trx);
        }

        return view('spesial-promo', compact('keywords'));
    }
}
