<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with(['product', 'user']);

        // 🔍 Tìm kiếm theo tên hoặc email khách hàng
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")->orWhere('email', 'like', "%$search%");
            });
        }
        // 📌 Lọc theo trạng thái (pending, approved, rejected)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // 🔒 Lọc theo is_active mặc định lả hoạt động
        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active == '1');
        }


        $comments = $query->orderByDesc('created_at')->paginate(10);

        // Lấy danh sách sản phẩm để hiển thị trong <select>
        $products = \App\Models\Product::orderBy('name')->get();

        return view('admin.comments.index', compact('comments', 'products'));
    }

    public function edit(Comment $comment)
    {
        return view('admin.comments.edit', compact('comment'));
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate([
            // 'content' => 'required|string',
            'status' => 'required|in:pending,approved,rejected',
            'reply' => 'nullable|string',
        ]);

        $comment->update([
            // 'content' => $request->content,
            'status' => $request->status,
            'reply' => $request->reply,
        ]);

        return redirect()->route('admin.comments.index')->with('success', 'Cập nhật bình luận thành công!');
    }

    //lock and unlock comment
    public function toggleStatus(Comment $comment)
    {
        $comment->is_active = !$comment->is_active; // Toggle status
        $comment->save();
        return redirect()->route('admin.comments.index')->with('success', 'Trạng thái bình luận đã được cập nhật thành công');
    }
}
