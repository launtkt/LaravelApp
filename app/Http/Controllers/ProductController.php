<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Image;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show($id)
    {
        $product = Product::with('images')->findOrFail($id);

        $reviews = $product
            ->reviews()
            ->with('user')
            ->latest()
            ->paginate(5, ['*'], 'reviews_page');
        $comments = $product
            ->comments()
            ->with('user')
            ->latest()
            ->paginate(5, ['*'], 'comments_page');

        // Tính đánh giá trung bình
        $averageStars = round($product->reviews()->avg('stars'));
        $totalReviews = $product->reviews()->count();

        return view('admin.products.detail', compact('product', 'reviews', 'comments', 'averageStars', 'totalReviews'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'sizes' => 'nullable|array',
            'images' => 'nullable|array|max:4',
            'images.*' => 'nullable|image|max:2048',
        ]);

        // Cập nhật trạng thái
        if ($data['quantity'] == 0) {
            $data['status'] = 'out';
        } elseif ($data['quantity'] < 50) {
            $data['status'] = 'low';
        } else {
            $data['status'] = 'in';
        }
        if ($request->hasFile('replace_images')) {
            foreach ($request->file('replace_images') as $imageId => $file) {
                if ($file) {
                    $image = Image::find($imageId);
                    if ($image && $image->product_id == $product->id) {
                        // Xoá ảnh cũ
                        $oldPath = public_path($image->path);
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }

                        // Lưu ảnh mới
                        $fileName = time() . '_' . $file->getClientOriginalName();
                        $file->move(public_path('products'), $fileName);

                        // Cập nhật đường dẫn ảnh
                        $image->path = 'products/' . $fileName;
                        $image->save();
                    }
                }
            }
        }
        if ($request->hasFile('images')) {
    foreach ($request->file('images') as $file) {
        $path = $file->store('products', 'public');

        $product->images()->create([
            'path' => 'storage/' . $path,
            'is_main' => false // hoặc logic nếu bạn muốn đặt ảnh chính
        ]);
    }
}

        $product->update($data);

        // Nếu có ảnh mới => Xoá ảnh cũ và lưu ảnh mới
        // Xử lý ảnh thay thế

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'sizes' => 'nullable|array',
            'images' => 'required|array|max:4',
            'images.*' => 'nullable|image|max:2048',
        ]);

        // Xử lý trạng thái dựa vào số lượng
        if ($data['quantity'] == 0) {
            $data['status'] = 'out';
        } elseif ($data['quantity'] < 50) {
            $data['status'] = 'low';
        } else {
            $data['status'] = 'in';
        }

        $product = Product::create($data);

        // Sinh mã sản phẩm
        $product->product_code = '#' . str_pad($product->category_id, 2, '0', STR_PAD_LEFT) . str_pad($product->id, 3, '0', STR_PAD_LEFT);
        $product->save();

        // Lưu ảnh
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $file) {
                if ($file) {
                    $fileName = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $uploadPath = public_path('products');

                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0755, true);
                    }

                    $file->move($uploadPath, $fileName);

                    Image::create([
                        'product_id' => $product->id,
                        'name' => null,
                        'path' => 'products/' . $fileName,
                        'is_main' => $index == 0,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function index()
    {
        $query = Product::query()->with('images', 'category');
        // Danh sách sản phẩm mặc định giảm dần theo ID


//tìm kiếm sản phẩm theo tên, mã sản phẩm
        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('product_code', 'like', "%$search%");
            });
        }
        // lọc theo trạng thái
        if (request()->filled('status')) {
            $status = request('status');
            $query->where('status', $status);
        }
        // lọc theo trạng thái hoạt động
        if (request()->filled('is_active')) {
            $isActive = request('is_active') == '1';
            $query->where('is_active', $isActive);
        }
        // lấy danh mục
        $categories = Category::all();
        // lọc theo danh mục

        if (request()->filled('category_id')) {
            $categoryId = request('category_id');
            $query->where('category_id', $categoryId);
        }
        $products = $query->orderBy('id', 'desc')->paginate(10);
        return view('admin.products.index', compact('products','categories'));
    }
    public function lowStock()
    {
        $products = Product::where('status', 'low')->with('images')->paginate(10);
        return view('admin.products.low_stock', compact('products'));
    }
   public function lock(Product $product)
{
    $product->update(['is_active' => false]);
    return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được khoá.');
}

public function unlock(Product $product)
{
    $product->update(['is_active' => true]);
    return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được mở khoá.');
}

}
