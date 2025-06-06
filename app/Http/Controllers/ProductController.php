<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $shortField = $request->get('sort_by', 'created_at');
        $shortDirection = $request->get('order', 'desc');
        $query->orderBy($shortField, $shortDirection);
        $products = $query->paginate(10);
        return view('admin.products.index', compact('products'));
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
            'image1' => 'nullable|image|max:2048',
            'image2' => 'nullable|image|max:2048',
            'image3' => 'nullable|image|max:2048',
            'image4' => 'nullable|image|max:2048',
        ]);

        // Xử lý trạng thái dựa vào số lượng
        if ($data['quantity'] == 0) {
            $data['status'] = 'Hết hàng';
        } elseif ($data['quantity'] < 50) {
            $data['status'] = 'Sắp hết hàng';
        } else {
            $data['status'] = 'Còn hàng';
        }

        // Tạo sản phẩm
    $product = Product::create($data);

    // Xử lý ảnh upload
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $file) {
            $path = $file->store('products', 'public');
            Image::create([
                'product_id' => $product->id,
                'name' => null, // admin tự sửa sau
                'path' => $path,
                'is_main' => $index == 0, // Ảnh đầu tiên là ảnh chính
            ]);
        }
    }

        // Sinh mã sản phẩm dạng #<IDdanh mục><IDsản phẩm>, ví dụ #01001
        $product->product_code = '#' . str_pad($product->category_id, 2, '0', STR_PAD_LEFT) . str_pad($product->id, 3, '0', STR_PAD_LEFT);
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Thêm sản phẩm thành công!');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        $product->sizes = is_array($product->sizes) ? $product->sizes : json_decode($product->sizes, true) ?? [];
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'nullable|string',
            'color' => 'nullable|string|max:50',
            'sizes' => 'nullable|array',
            'image1' => 'nullable|image|max:2048',
            'image2' => 'nullable|image|max:2048',
            'image3' => 'nullable|image|max:2048',
            'image4' => 'nullable|image|max:2048',
        ]);

        // Xử lý trạng thái dựa vào số lượng
        if ($data['quantity'] == 0) {
            $data['status'] = 'Hết hàng';
        } elseif ($data['quantity'] < 50) {
            $data['status'] = 'Sắp hết hàng';
        } else {
            $data['status'] = 'Còn hàng';
        }

        // Nếu có ảnh mới, xóa ảnh cũ và thêm ảnh mới
        if ($request->hasFile('images')) {
            // Xóa ảnh cũ trong DB và storage
            foreach ($product->images as $img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
            // Lưu ảnh mới
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('products', 'public');
                Image::create([
                    'product_id' => $product->id,
                    'name' => null,
                    'path' => $path,
                    'is_main' => $index == 0,
                ]);
            }
        }

        $product->update($data);

        // Cập nhật lại mã sản phẩm nếu đổi danh mục
        $product->product_code = '#' . str_pad($product->category_id, 2, '0', STR_PAD_LEFT) . str_pad($product->id, 3, '0', STR_PAD_LEFT);
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        // Xóa 4 ảnh nếu có
        for ($i = 1; $i <= 4; $i++) {
            if ($product->{'image' . $i}) {
                Storage::disk('public')->delete($product->{'image' . $i});
            }
        }
        $product->delete();
        return redirect()->route('admin.products.index');
    }
}
