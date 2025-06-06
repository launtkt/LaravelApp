<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $fillable = ['order_id', 'status', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getStatusLabelAttribute()
    {
        $map = [
            'pending' => 'Chờ xác nhận',
            'shipping' => 'Đang giao',
            'completed' => 'Đã giao',
            'cancelled' => 'Đã huỷ',
            'returned' => 'Hoàn trả'
        ];
        return $map[$this->status] ?? ucfirst($this->status);
    }
}
