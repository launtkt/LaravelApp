<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'voucher', 'items.product']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // 🔒 Lọc theo is_active mặc định lả hoạt động
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }
        $orders = $query->latest()->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'voucher', 'items.product.images'])->findOrFail($id);
        return view('admin.orders.detail', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('admin.orders.edit', compact('order'));
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipping,delivered,returned,cancelled',
        ]);

        $order->status = $request->input('status');
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Cập nhật trạng thái thành công.');
    }
    public function create()
    {
        $users = User::all();
        $vouchers = Voucher::where('status', true)->get();

        return view('admin.orders.create', compact('users', 'vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'voucher_id' => 'nullable|exists:vouchers,id',
            'status' => 'required|in:pending,confirmed,processing,shipping,delivered,returned,cancelled',
        ]);

        $order = Order::create([
            'user_id' => $request->user_id,
            'voucher_id' => $request->voucher_id,
            'status' => $request->status,
        ]);
        if ($request->filled('voucher_id')) {
            $user = User::find($request->user_id);
            $voucherId = $request->voucher_id;

            $userVoucher = $user->vouchers()->where('voucher_id', $voucherId)->first();

            if ($userVoucher && $userVoucher->pivot->uses_remaining > 0) {
                $newRemaining = $userVoucher->pivot->uses_remaining - 1;

                $user->vouchers()->updateExistingPivot($voucherId, [
                    'uses_remaining' => $newRemaining,
                    'used' => $newRemaining <= 0,
                    'used_at' => now(),
                ]);
            }
        }

        return redirect()->route('admin.orders.index')->with('success', 'Tạo đơn hàng thành công.');
    }
    // Lock or unlock order
    public function toggleStatus($id)
    {
        $order = Order::findOrFail($id);
        $order->is_active = !$order->is_active; // Toggle status
        $order->save();
        return redirect()->route('admin.orders.index')->with('success', 'Trạng thái đơn hàng đã được cập nhật thành công');
    }
  

}
