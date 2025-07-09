<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use Illuminate\Http\Request;

class CCartController extends Controller
{
    public function index(Request $request)
    {
        $account = $request->user();

        $cart = CartItem::with('product')
            ->where('account_id', $account->id)
            ->get();

        return response()->json($cart);
    }

    public function store(Request $request)
    {
        $account = $request->user();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::where('account_id', $account->id)
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($item) {
            $item->quantity += $validated['quantity'];
            $item->save();
        } else {
            $item = CartItem::create([
                'account_id' => $account->id,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return response()->json([
            'message' => 'Đã thêm vào giỏ hàng',
            'item' => $item,
        ]);
    }

    public function update(Request $request, $id)
    {
        $account = $request->user();

        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::where('account_id', $account->id)
            ->findOrFail($id);

        $item->update(['quantity' => $validated['quantity']]);

        return response()->json([
            'message' => 'Cập nhật số lượng thành công',
            'item' => $item,
        ]);
    }
    public function destroy(Request $request, $id)
    {
        $account = $request->user();

        $item = CartItem::where('account_id', $account->id)
            ->findOrFail($id);

        $item->delete();

        return response()->json([
            'message' => 'Đã xoá khỏi giỏ hàng',
        ]);
    }
}
