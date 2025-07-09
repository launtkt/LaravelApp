<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;

class CProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::query();

            if ($request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->category_id) {
                $query->where('category_id', $request->category_id);
            }

            $products = $query->paginate(10);

            $data = [
                'data' => $products->map(
                    fn($p) => [
                        'id' => $p->id,
                        'name' => $p->name,
                        'price' => $p->price,
                        'thumbnail' => optional($p->images()->where('is_main', 1)->first())->path,
                        'status' => $p->status,
                    ],
                ),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $products->total(),
                ],
            ];

            return response()->json($data, 200, [], JSON_PRETTY_PRINT);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function latest()
    {
        $products = Product::latest()->take(6)->get();

        return response()->json(
            $products->map(
                fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price,
                    'thumbnail' => optional($p->images()->where('is_main', 1)->first())->path,
                ],
            ),
            200,
            [],
            JSON_PRETTY_PRINT,
        );
    }

    public function featured()
    {
        $products = Product::where('status', 'Sắp hết hàng')->take(6)->get();

        return response()->json(
            $products->map(
                fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price,
                    'thumbnail' => optional($p->images()->where('is_main', 1)->first())->path,
                ],
            ),
            200,
            [],
            JSON_PRETTY_PRINT,
        );
    }

    public function show($id)
    {
        $p = Product::with(['images', 'category'])->findOrFail($id);

        return response()->json(
            [
                'id' => $p->id,
                'name' => $p->name,
                'price' => $p->price,
                'description' => $p->description,
                'status' => $p->status,
                'color' => $p->color,
                'sizes' => $p->sizes,
                'category' => $p->category->name ?? null,
                'images' => $p->images->pluck('path'),
            ],
            200,
            [],
            JSON_PRETTY_PRINT,
        );
    }

    public function related($id)
    {
        $product = Product::findOrFail($id);

        $related = Product::where('category_id', $product->category_id)->where('id', '!=', $id)->take(5)->get();

        return response()->json(
            $related->map(
                fn($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'price' => $p->price,
                    'thumbnail' => optional($p->images()->where('is_main', 1)->first())->path,
                ],
            ),
            200,
            [],
            JSON_PRETTY_PRINT,
        );
    }

    public function rating($id)
    {
        $avg = Review::where('product_id', $id)->where('status', 'approved')->avg('stars');

        return response()->json(
            [
                'rating' => round($avg, 1),
            ],
            200,
            [],
            JSON_PRETTY_PRINT,
        );
    }
}
