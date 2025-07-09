<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index()
    {
        $products = Product::all();
        if (request()->filled('product_id')) {
            $productId = request()->input('product_id');
            $images = Image::where('product_id', $productId)->paginate(12);
            return view('admin.images.index', compact('images', 'products', 'productId'));
        }
        //tìm kiếm ảnh theo sản phẩm
        if (request()->filled('search')) {
            $search = request()->input('search');
            $images = Image::whereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })->paginate(12);
            return view('admin.images.index', compact('images', 'products', 'search'));
        }
        // sắp xếp ảnh theo sản phẩm
        if (request()->filled('sort')) {
            $sort = request()->input('sort');
            $images = Image::with('product')
                ->orderBy('product_id', $sort === 'asc' ? 'asc' : 'desc')
                ->paginate(12);
            return view('admin.images.index', compact('images', 'products', 'sort'));
        }
        //Lấy tất cả ảnh sắp xếp theo sản phẩm giảm dần
        $images = Image::with('product')->orderBy('product_id', 'desc')->paginate(12);
        // $images = Image::with('product')->paginate(12);
        return view('admin.images.index', compact('images', 'products'));
    }

    public function create(Request $request)
    {
            // Lấy danh sách sản phẩm thiếU ảnh chính, ảnh phụ hoặc chưa có ảnh để hiển thị trong dropdown
            $products = Product::with('images')->get()->filter(function ($product) {
    $main = $product->images->where('is_main', true)->isNotEmpty();
    $additional = $product->images->where('is_main', false)->count();

    return !$main || $additional < 3;
});
            // $products = Product::with('images')->get();

            $selectedProductId = $request->input('product_id');
            $selectedProduct = null;
            $needMainImage = false;
            $needAdditional = 0;

            if ($selectedProductId) {
                $selectedProduct = Product::with('images')->findOrFail($selectedProductId);

                $mainImage = $selectedProduct->images->where('is_main', true)->first();
                $additionalCount = $selectedProduct->images->where('is_main', false)->count();

                $needMainImage = !$mainImage;
                $needAdditional = max(0, 3 - $additionalCount);
            }

            return view('admin.images.create', compact('products', 'selectedProductId', 'selectedProduct', 'needMainImage', 'needAdditional'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_main.*' => 'required|in:0,1',
        ]);

        foreach ($request->file('images') as $index => $file) {
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('products'), $fileName);

            // Nếu đây là ảnh chính thì reset ảnh chính cũ
            if ($request->is_main[$index]) {
                Image::where('product_id', $request->product_id)->update(['is_main' => false]);
            }

            Image::create([
                'product_id' => $request->product_id,
                'path' => 'products/' . $fileName,
                'is_main' => $request->is_main[$index],
            ]);
        }

        return redirect()->route('admin.images.index')->with('success', 'Thêm ảnh thành công!');
    }

    public function destroy(Image $image)
    {
        $filePath = public_path($image->path);
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $image->delete();
        return redirect()->route('admin.images.index')->with('success', 'Xóa ảnh thành công!');
    }
    public function edit(Image $image)
    {
        return view('admin.images.edit', compact('image'));
    }

    public function update(Request $request, Image $image)
    {
        $data = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_main' => 'required|boolean',
        ]);

        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            $oldPath = public_path($image->path);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $file = $request->file('image');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('products'), $fileName);
            $image->path = 'products/' . $fileName;
        }

        // Cập nhật is_main (luôn là 1 nếu ảnh chính, 0 nếu ảnh phụ)
        $image->is_main = $request->is_main;

        $image->save();

        return redirect()->route('admin.images.index')->with('success', 'Cập nhật ảnh thành công!');
    }
}
