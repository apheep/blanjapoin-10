<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    use HasFactory;

    protected $table = 'withdraw_requests';

    protected $fillable = [
        'merchant_id',
        'nama',
        'metode_penarikan',
        'no_rekening',
        'no_ewallet',
        'jumlah',
        'transaction_id',
        'status',
        'approved_by',
        'approved_at',
        'dec_reject',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan Merchant
    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id');
    }

    // Relationship dengan User (admin yang approve)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    // Helper method untuk mendapatkan nama metode penarikan
    public function getMetodePenarikanNameAttribute()
    {
        $methods = [
            'bca' => 'BCA',
            'bni' => 'BNI',
            'bri' => 'BRI',
            'mandiri' => 'Mandiri',
            'linkaja' => 'Link Aja',
            'dana' => 'Dana',
        ];
        
        return $methods[$this->metode_penarikan] ?? $this->metode_penarikan;
    }

    // Helper method untuk mendapatkan nomor rekening atau e-wallet
    public function getAccountNumberAttribute()
    {
        $isEWallet = in_array($this->metode_penarikan, ['linkaja', 'dana']);
        
        if ($isEWallet) {
            return $this->no_ewallet;
        }
        
        return $this->no_rekening;
    }

    // Helper method untuk format no rekening/e-wallet untuk display
    public function getFormattedAccountNumberAttribute()
    {
        $isEWallet = in_array($this->metode_penarikan, ['linkaja', 'dana']);
        
        if ($isEWallet && $this->no_ewallet) {
            // no_ewallet is now stored with +62 prefix (consistent with merchant wa_pic)
            // Return as-is since it already includes +62
            return $this->no_ewallet;
        }
        
        return $this->no_rekening ?? '';
    }
}
