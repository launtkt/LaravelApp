<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CCommentController extends Controller
{
    // Gửi bình luận
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'content' => 'required|string|max:1000',
        ]);

        $recent = Comment::where('account_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->where('content', $validated['content'])
            ->where('created_at', '>', now()->subMinutes(2))
            ->first();

        if ($recent) {
            return response()->json(['message' => 'Bạn vừa gửi bình luận giống nội dung này. Vui lòng đợi.'], 429);
        }

        $comment = Comment::create([
            'account_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'content' => $validated['content'],
        ]);

        return response()->json(['message' => 'Bình luận thành công', 'data' => $comment], 201);
    }

    // Lấy danh sách bình luận
    public function list($productId)
    {
        $comments = Comment::with('account:id,name')
            ->where('product_id', $productId)
            ->latest()
            ->paginate(10);

        return response()->json($comments);
    }
}
