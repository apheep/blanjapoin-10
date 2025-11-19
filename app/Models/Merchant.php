<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Merchant extends Model
{
    use HasFactory;

    protected $table = 'merchants';

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'daerah',
        'nama_merchant',
        'kategori',
        'logo_merchant'
    ];

    public function keywords()
    {
        return $this->hasMany(Keyword::class, 'merchant_key', 'id');
    }
}

