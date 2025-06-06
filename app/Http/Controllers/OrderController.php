<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // Danh sách đơn hàng + tìm kiếm
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'orderItems.product']);
        if ($request->filled('search')) {
            $query->where('code', 'like', '%'.$request->search.'%')
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('name', 'like', '%'.$request->search.'%')
                        ->orWhere('email', 'like', '%'.$request->search.'%');
                  });
        }
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    // Xem chi tiết đơn hàng
    public function show($id)
    {
        $order = Order::with([
            'customer',
            'orderItems.product',
            'statusHistories.user'
        ])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    // Các hàm create, store, edit, update, destroy có thể bổ sung nếu cần
}
