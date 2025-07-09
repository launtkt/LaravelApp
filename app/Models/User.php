<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['username', 'email', 'password', 'full_name', 'phone', 'address', 'avatar', 'gender', 'birthday', 'role', 'status'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'birthday' => 'date',
        'status' => 'boolean',
    ];

    // Các quan hệ (ví dụ nếu dùng)
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function vouchers()
{
    return $this->belongsToMany(Voucher::class, 'user_voucher')
                ->withPivot(['used', 'used_at', 'uses_remaining'])
                ->withTimestamps();
}

}
