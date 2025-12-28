<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClickHistory extends Model
{
    use HasFactory;

    protected $table = 'click_history';

    protected $fillable = [
        'merchant_id',
        'keyword_id',
        'ip_address',
        'device_id',
        'clicked_at',
        'user_agent',
        'referer',
    ];

    protected $casts = [
        'clicked_at' => 'datetime',
    ];

    // Relationships
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    public function keyword()
    {
        return $this->belongsTo(Keyword::class, 'keyword_id', 'keyword_id');
    }
}
