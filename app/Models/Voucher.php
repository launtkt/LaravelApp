<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['code', 'type', 'value', 'min_order_amount', 'max_uses', 'used', 'start_date', 'end_date', 'status', 'description', 'max_discount', 'is_active'];
    protected $casts = [
        // ép kiểu khi gán giá trị cho các trường trong DB
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'status' => 'boolean',
    ];
    public function isValid()
    {
        $now = Carbon::now(); //lấy tg thực
        return $this->status &&
            (!$this->start_date || $this->start_date <= $now) && //nếu start_date tồn tại kiểm tra strar_date với $now
            (!$this->end_date || $this->end_date >= $now) &&
            ($this->max_uses === null || $this->used < $this->max_uses); //nếu max_use = null thì không giới hạn dùng, kiểm tra đã dùng với max dùng nếu có
    }
    public function cart()
    {
        return $this->hasMany('cart');
    }
    public function users()
{
    return $this->belongsToMany(User::class, 'user_voucher')
                ->withPivot(['used', 'used_at', 'uses_remaining'])
                ->withTimestamps();
}

}
