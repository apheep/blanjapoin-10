<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_key',
        'nama_produk',
        'redeem',
        'diskon',
        'skb',
        'start_date',
        'end_date',
        'link_kv_google',
        'stock',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_key', 'id');
    }
}
