<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iklan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image_path',
        'link_iklan',
        'territorial',
        'regional',
        'branch',
        'cluster',
        'merchant_key',
        'merchant_keys',
        'order',
    ];

    protected $casts = [
        'merchant_keys' => 'array',
    ];

    /**
     * Get the merchant that owns the iklan (legacy single merchant).
     */
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_key', 'id');
    }

    /**
     * Get all merchants associated with this iklan from merchant_keys JSON.
     */
    public function getMerchantsAttribute()
    {
        $merchantIds = $this->merchant_keys ?? [];
        if (empty($merchantIds) && $this->merchant_key) {
            $merchantIds = [$this->merchant_key];
        }
        if (empty($merchantIds)) {
            return collect([]);
        }
        return Merchant::whereIn('id', $merchantIds)->get();
    }
}
