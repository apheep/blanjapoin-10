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
        'detail_daerah',
        'lat',
        'long',
        'link_gmap'
    ];

    // Jangan cast lat dan long, biarkan sebagai string/decimal dari database
    // Ini mempertahankan format asli yang diinput user

    public function keywords()
    {
        return $this->hasMany(Keyword::class, 'merchant_key', 'id');
    }
}

