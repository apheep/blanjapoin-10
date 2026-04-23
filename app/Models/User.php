<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'role',
        'can_approve',
        'no_hp',
        'email',
        'otp',
        'otp_expires_at',
        'user_level',
        'area',
        'regional',
        'branch',
        'sub_branch',
        'cluster',
        'city',
        'area_level',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'username_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the can_approve attribute dynamically if overridden in a user session.
     * Overrides the persistent DB value for bypass testing.
     */
    public function getCanApproveAttribute($value)
    {
        try {
            if (app()->bound('request') && request()->hasSession() && request()->session()->has('bypass_can_approve')) {
                return request()->session()->get('bypass_can_approve');
            }
        } catch (\Throwable $e) {}
        
        return $value;
    }

    /**
     * Scope to get last login time
     */
    public function getLastLoginAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('Y-m-d H:i') : 'Belum pernah login';
    }
}
