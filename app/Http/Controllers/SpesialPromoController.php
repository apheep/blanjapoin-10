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
        $keywords = Keyword::with('merchant')
            ->where('is_special_promo', 1)
            ->where('is_active', 1)
            ->where('status', 'approve')
            ->orderBy('id', 'desc')
            ->get();

        return view('spesial-promo', compact('keywords'));
    }
}
