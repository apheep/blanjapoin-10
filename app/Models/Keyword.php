<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        'skb',
        'start_date',
        'end_date',
        'image',
        'stock',
        'trx',
        'sisa_stock',
        'status',
    ];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_key', 'id');
    }
}
