<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Product;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class COrderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();

            $orders = Order::with(['voucher', 'items.product'])
                ->where('user_id', $user->id)
                ->orderByDesc('created_at')
                ->paginate(10);

            return response()->json($orders, 200, [], JSON_PRETTY_PRINT);
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'message' => 'Lỗi khi lấy danh sách đơn hàng.',
                    'error' => $e->getMessage(),
                ],
                500,
                [],
                JSON_PRETTY_PRINT,
            );
        }
    }

    public function show(Request $request, $id)
    {
        try {
            $user = $request->user();

            $order = Order::with(['voucher', 'items.product.images'])
                ->where('user_id', $user->id)
                ->findOrFail($id);

            return response()->json($order, 200, [], JSON_PRETTY_PRINT);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(
                [
                    'message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn.',
                ],
                404,
                [],
                JSON_PRETTY_PRINT,
            );
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'message' => 'Lỗi khi lấy chi tiết đơn hàng.',
                    'error' => $e->getMessage(),
                ],
                500,
                [],
                JSON_PRETTY_PRINT,
            );
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'voucher_code' => 'nullable|string',
            ]);

            $user = $request->user();

            DB::beginTransaction();

            $voucher = null;
            $discount = 0;

            // Kiểm tra voucher nếu có
            if ($request->filled('voucher_code')) {
                $voucher = Voucher::where('code', $request->voucher_code)->first();

                if (!$voucher || !$voucher->isValidFor($user)) {
                    return response()->json(
                        [
                            'message' => 'Voucher không hợp lệ hoặc đã hết lượt dùng.',
                        ],
                        400,
                        [],
                        JSON_PRETTY_PRINT,
                    );
                }

                $discount = $voucher->discount_amount;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'voucher_id' => $voucher?->id,
                'discount' => $discount,
                'status' => 'pending',
                'is_active' => true,
            ]);

            $total = 0;

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $quantity = $item['quantity'];
                $price = $product->price;

                $total += $price * $quantity;

                Order_item::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                ]);
            }

            $order->total_price = $total;
            $order->final_price = max(0, $total - $discount);
            $order->save();

            // Cập nhật lượt dùng voucher
            if ($voucher) {
                $userVoucher = $user->vouchers()->where('voucher_id', $voucher->id)->first();

                if ($userVoucher && $userVoucher->pivot->uses_remaining > 0) {
                    $newRemaining = $userVoucher->pivot->uses_remaining - 1;

                    $user->vouchers()->updateExistingPivot($voucher->id, [
                        'uses_remaining' => $newRemaining,
                        'used' => $newRemaining <= 0,
                        'used_at' => now(),
                    ]);
                }
            }

            DB::commit();

            return response()->json(
                [
                    'message' => 'Đặt hàng thành công.',
                    'order' => $order,
                ],
                201,
                [],
                JSON_PRETTY_PRINT,
            );
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'message' => 'Dữ liệu không hợp lệ.',
                    'errors' => $e->errors(),
                ],
                422,
                [],
                JSON_PRETTY_PRINT,
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(
                [
                    'message' => 'Đã xảy ra lỗi khi đặt hàng.',
                    'error' => $e->getMessage(),
                ],
                500,
                [],
                JSON_PRETTY_PRINT,
            );
        }
    }
    public function destroy(Request $request, $id)
    {
        try {
            $user = $request->user();

            $order = Order::where('user_id', $user->id)
                ->where('status', 'pending') // Chỉ cho phép huỷ nếu đang chờ xác nhận
                ->findOrFail($id);

            $order->status = 'cancelled';
            $order->save();

            return response()->json(
                [
                    'message' => 'Đơn hàng đã được huỷ thành công.',
                    'order' => $order,
                ],
                200,
                [],
                JSON_PRETTY_PRINT,
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(
                [
                    'message' => 'Không tìm thấy đơn hàng phù hợp để huỷ.',
                ],
                404,
                [],
                JSON_PRETTY_PRINT,
            );
        } catch (\Throwable $e) {
            return response()->json(
                [
                    'message' => 'Đã xảy ra lỗi khi huỷ đơn hàng.',
                    'error' => $e->getMessage(),
                ],
                500,
                [],
                JSON_PRETTY_PRINT,
            );
        }
    }
}
