<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
            return view('admin.categories.index', [
                'categories' => $query->paginate(10)->withQueryString(),
                'search' => $search,
            ]);
        }
        if ($request->has('status') && $request->status != '') {
            $status = $request->status;
            if ($status == '1') {
                $query->where('status', true);
            } elseif ($status == '0') {
                $query->where('status', false);
            }
        }
        if ($request->has('sort') && $request->sort != '') {
            $sort = $request->sort;
            if ($sort == 'asc') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort == 'desc') {
                $query->orderBy('created_at', 'desc');
            }
        }

        $categories =$query->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Thêm danh mục thành công!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    public function lock(Category $category)
    {
        $category->update(['status' => false]); // hoặc 0
        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được khoá!');
    }
public function unlock(Category $category )
    {
        $category->update(['status' => true]);

        return redirect()->route('admin.categories.index')->with('success', 'Danh mục đã được mở khoá!');
    }
}
