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
        'cta_link',
        'redeem',
        'diskon',
        'skb',
        'start_date',
        'end_date',
        'image',
        'stock',
        'status',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_key', 'id');
    }
}
