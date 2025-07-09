<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class CCheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $account = Auth::guard('sanctum')->user();

        $cartItems = CartItem::with('product')
            ->where('account_id', $account->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Giỏ hàng trống'], 400);
        }

        $request->validate([
            'voucher_code' => 'nullable|string',
        ]);

        // Tính tổng giá trị đơn hàng gốc
        $total = $cartItems->sum(fn($item) => $item->product->price * $item->quantity);

        // Xử lý voucher
       // Kiểm tra xem có nhập voucher không
$voucher = null;
$voucherId = null;

if ($request->filled('voucher_code')) {
    Log::info("Checkout - Nhận được voucher_code: " . $request->voucher_code);

    $voucher = Voucher::where('code', $request->voucher_code)->first();

    if (!$voucher) {
        Log::warning("Checkout - Voucher không tồn tại: " . $request->voucher_code);
        return response()->json(['message' => 'Voucher không tồn tại.'], 400);
    }

    if (!$voucher->isValid()) {
        Log::warning("Checkout - Voucher không hợp lệ: ", [
            'code' => $voucher->code,
            'status' => $voucher->status,
            'start_date' => $voucher->start_date,
            'end_date' => $voucher->end_date,
            'max_uses' => $voucher->max_uses,
            'used' => $voucher->used,
        ]);
        return response()->json(['message' => 'Voucher không hợp lệ hoặc đã hết hạn.'], 400);
    }

    $voucherId = $voucher->id;
    Log::info("Checkout - Voucher hợp lệ và được áp dụng: " . $voucher->code);
} else {
    Log::info("Checkout - Không có mã voucher được nhập.");
}


        // Tạo đơn hàng
        $order = Order::create([
            'account_id'   => $account->id,
            'voucher_id'   => $voucherId,
            'status'       => 'pending',
            'total_price'  => $total,
        ]);

        // Tạo các dòng sản phẩm
        foreach ($cartItems as $item) {
            Order_item::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
        }

        // Xoá giỏ hàng
        CartItem::where('account_id', $account->id)->delete();

        // Load lại để tính giảm giá
        $order->load(['items.product', 'voucher']);

        // Tính giảm giá và giá cuối cùng
        $discount = $order->discount_amount;
        $finalPrice = $order->final_price;

        // Cập nhật đơn hàng
        $order->update([
            'discount'     => $discount,
            'final_price'  => $finalPrice,
        ]);

        // Tăng lượt dùng voucher nếu có
        if ($voucher) {
            $voucher->increment('used');
        }

        return response()->json([
            'message' => 'Đặt hàng thành công',
            'order' => [
                'id'           => $order->id,
                'code'         => $order->code,
                'status'       => $order->status,
                'account_id'   => $order->account_id,
                'voucher'      => $order->voucher?->code,
                'total_price'  => $order->total_price,
                'discount'     => $discount,
                'final_price'  => $finalPrice,
                'products'     => $order->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'name'       => $item->product->name ?? 'N/A',
                        'price'      => $item->price,
                        'quantity'   => $item->quantity,
                        'subtotal'   => $item->price * $item->quantity,
                    ];
                }),
            ]
        ]);
    }
}
