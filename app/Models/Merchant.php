<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $table = 'merchants';

    protected $primaryKey = 'no';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'no',
        'daerah',
        'nama_merchant',
        'link_kv_google',
        'kategori',
        'poin',
        'promo',
    ];

    public function keywords()
    {
        return $this->hasMany(Keyword::class, 'merchant_key', 'no');
    }
}

