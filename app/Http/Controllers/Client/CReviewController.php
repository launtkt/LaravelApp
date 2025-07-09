<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CReviewController extends Controller
{
    // Gửi đánh giá
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'stars' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
            'image_path' => 'nullable|string|max:255'
        ]);

        // Kiểm tra đã đánh giá chưa
        $exists = Review::where('account_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->exists();

        if ($exists) {
            return response()->json(['message' => 'Bạn đã đánh giá sản phẩm này rồi.'], 409);
        }

        $review = Review::create([
            'account_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'stars' => $validated['stars'],
            'review' => $validated['review'],
            'image_path' => $validated['image_path'] ?? null,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Đánh giá thành công', 'data' => $review], 201);
    }

    // Lấy danh sách đánh giá đã duyệt của sản phẩm
    public function list($productId)
    {
        $reviews = Review::with('account:id,name')
            ->where('product_id', $productId)
            ->where('status', 'approved')
            ->latest()
            ->paginate(10);

        return response()->json($reviews);
    }
}
