<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function index(Request $request){
        $query = Voucher::query();
        if ($search = $request ->input('search'))
        {
            $query->where('code','like','%'.$search.'%');
        }
        if ($request->filled('status'))
        {
            $query->where('status',$request->status);
        }
        // 🔒 Lọc theo trạng thái hoạt độn
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }
        // Lọc theo loại voucher
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        $vouchers =$query->latest()->paginate(10);
        return view('admin.vouchers.index',compact('vouchers'));
    }
    public function create(){
        return view('admin.vouchers.create');
    }
   public function store(Request $request)
{
    $data = $request->validate([
        'code' => 'required|unique:vouchers,code',
        'type' => 'required|in:percentage,fixed',
        'value' => 'required|numeric|min:0',
        'min_order_amount' => 'nullable|numeric|min:0',
        'max_uses' => 'nullable|integer|min:0',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'status' => 'required|boolean',
        'max_discount' => 'nullable|numeric|min:0',
        'description' => 'nullable|string|max:65535',
    ]);

    if ($data['type'] === 'fixed') {
        $data['max_discount'] = $data['value'];
    }

    Voucher::create($data);


    return redirect()->route('admin.vouchers.index')->with('success', 'Tạo voucher thành công');
}

    public function edit($id){
        $voucher = Voucher::findOrFail($id);
        return view('admin.vouchers.edit',compact('voucher'));
    }
    public function update(Request $request, $id)
{
    $voucher = Voucher::findOrFail($id);

    $data = $request->validate([
        'code' => 'required|string|max:255|unique:vouchers,code,' . $voucher->id,
        'type' => 'required|in:percentage,fixed',
        'value' => 'required|numeric|min:0',
        'min_order_amount' => 'nullable|numeric|min:0',
        'max_uses' => 'nullable|integer|min:0',
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'status' => 'required|boolean',
        'description' => 'nullable|string|max:65535',
        'max_discount' => 'nullable|numeric|min:0',
    ]);

    if ($data['type'] === 'fixed') {
        $data['max_discount'] = $data['value'];
    }
    $now = now();
    if ($data['start_date'] > $now || $data['end_date'] < $now){
        $data['status'] = false;
    }

    $voucher->update($data);

    return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật voucher thành công');
}

    // Lock or unlock voucher
    public function toggleStatus($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->is_active = !$voucher->is_active; // Toggle status
        $voucher->save();
        return redirect()->route('admin.vouchers.index')->with('success', 'Trạng thái voucher đã được cập nhật thành công');
    }
}
