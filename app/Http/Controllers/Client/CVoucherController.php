<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Voucher;


class CVoucherController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'total' => 'required|numeric|min:0',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json(['message' => 'Mã giảm giá không tồn tại.'], 404);
        }

        if (!$voucher->isValid()) {
            return response()->json(['message' => 'Mã giảm giá đã hết hạn hoặc vượt giới hạn sử dụng.'], 400);
        }

        if ($request->total < $voucher->min_order_amount) {
            return response()->json(['message' => 'Đơn hàng không đạt giá trị tối thiểu để sử dụng mã giảm giá.'], 400);
        }

        // Tính toán giá trị giảm
        $discount = 0;
        if ($voucher->type === 'fixed') {
            $discount = $voucher->value;
        } elseif ($voucher->type === 'percent') {
            $discount = $request->total * $voucher->value / 100;
            if ($voucher->max_discount && $discount > $voucher->max_discount) {
                $discount = $voucher->max_discount;
            }
        }

        return response()->json([
            'message' => 'Mã giảm giá hợp lệ.',
            'discount' => $discount,
            'total_after_discount' => $request->total - $discount,
            'voucher_code' => $voucher->code
        ]);
    }
}
