<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'code', 'customer_id', 'customer_name', 'customer_email', 'customer_phone', 'customer_address',
        'total_amount', 'shipping_fee', 'discount_code', 'discount_amount', 'payment_method', 'status', 'note'
    ];

    // Khách hàng (nếu có bảng customers)
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // Danh sách sản phẩm trong đơn
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Lịch sử trạng thái đơn hàng
    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class);
    }
}
