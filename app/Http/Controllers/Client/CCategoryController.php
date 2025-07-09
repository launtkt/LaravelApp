<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CCategoryController extends Controller
{
    // Danh sách danh mục
    public function index()
{
    $categories = Category::all(['id', 'name', 'image_cate']);

    return response()->json([
        'data' => $categories->map(fn($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'image' => $c->image_cate
                ? asset('storage/' . $c->image_cate)
                : asset('images/default-category.png') // ảnh mặc định nếu chưa có
        ])
    ], 200, [], JSON_PRETTY_PRINT);
}


    // Danh sách sản phẩm theo danh mục
    public function products($id)
    {
        $category = Category::findOrFail($id);
        $products = Product::where('category_id', $id)->paginate(10);

        return response()->json([
            'category' => $category->name,
            'products' => $products->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'thumbnail' => optional($p->images()->where('is_main', 1)->first())->path,
                'status' => $p->status
            ]),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total()
            ]
        ], 200, [], JSON_PRETTY_PRINT);
    }
}

