<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // List all reviews with optional search
   public function index(Request $request)
{
    $query = Review::with(['product', 'user']);

    // 🔍 Tìm kiếm theo tên hoặc email khách hàng
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->whereHas('user', function ($q) use ($search) {
            $q->where('full_name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%");
        });
    }
    // 📌 Lọc theo trạng thái (pending, approved, rejected)
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    // 🔒 Lọc theo is_active mặc định lả hoạt độn
    if ($request->filled('is_active')) {
        $query->where('is_active', $request->is_active == '1');
    }
    $reviews = $query->orderByDesc('created_at')->paginate(10);

    // Lấy danh sách sản phẩm để hiển thị trong <select>
    $products = \App\Models\Product::orderBy('name')->get();

    return view('admin.reviews.index', compact('reviews', 'products'));
}

    public function edit(Review $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }
    public function update(Request $request, Review $review)
    {
        $request->validate([
            'stars' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'status' => 'required|in:pending,approved,rejected',
            'reply' => 'nullable|string|max:1000',
        ]);
        $review->update([
            'stars' => $request->stars,
            'review' => $request->review,
            'status' => $request->status,
            'reply' => $request->reply,
        ]);
        return redirect()->route('admin.reviews.index')->with('success', 'Cập nhật đánh giá thành công.');
        return view('admin.reviews.edit', compact('review'));
    }
    public function show($id)
    {
        $review = Review::with(['product', 'user'])->findOrFail($id);
        return view('admin.reviews.show', compact('review'));
    }

    // lock and unlock review
    public function toggleStatus(Review $review)
    {
        $review->is_active = !$review->is_active; // Toggle status
        $review->save();
        return redirect()->route('admin.reviews.index')->with('success', 'Trạng thái đánh giá đã được cập nhật thành công');
    }
}
