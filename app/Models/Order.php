<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $fillable = ['user_id', 'status', 'total_price', 'code', 'discount', 'final_price', 'voucher_id', 'is_active'];
    protected $appends = ['status_text'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(Order_item::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->code)) {
                do {
                    $code = 'DH' . strtoupper(Str::random(6));
                } while (Order::where('code', $code)->exists());

                $order->code = $code;
            }
        });
    }

    public function getTotalPriceAttribute()
    {
        return $this->items->sum(fn($item) => $item->product->price * $item->quantity);
    }

    public function getDiscountAmountAttribute()
    {
        $total = $this->total_price;
        $voucher = $this->voucher;
        $discount = 0;

        if ($voucher) {
            if ($voucher->type === 'percentage') {
                $discount = min(intval($total * $voucher->value / 100), $voucher->max_discount ?? INF);
            } elseif ($voucher->type === 'fixed') {
                $discount = $voucher->value;
            }
        }

        return $discount;
    }

    public function getFinalPriceAttribute()
    {
        return max(0, $this->total_price - $this->discount_amount);
    }
    public function getStatusTextAttribute()
    {
        return match ($this->status) {
            'pending' => 'Đang chờ',
            'confirmed' => 'Đã xác nhận',
            'processing' => 'Đang xử lý',
            'shipping' => 'Đang giao hàng',
            'delivered' => 'Đã giao hàng',
            'returned' => 'Đã trả hàng',
            'cancelled' => 'Đã huỷ',
            default => 'Không xác định',
        };
    }
}

