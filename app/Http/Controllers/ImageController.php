<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index()
    {
        $images = \App\Models\Image::all();
        $files = Storage::disk('public')->files('products');
        $imagePaths = $images->pluck('path')->toArray();

        // Tự động thêm file mới vào DB nếu chưa có
        foreach ($files as $file) {
            if (!in_array($file, $imagePaths)) {
                \App\Models\Image::create([
                    'name' => null, // Admin sẽ tự sửa sau
                    'path' => $file,
                    'is_main' => false,
                ]);
            }
        }

        // Lấy lại danh sách sau khi thêm mới
        $images = \App\Models\Image::all();

        return view('admin.images.index', compact('images'));
    }

    public function update(Request $request, $id)
    {
        $image = Image::findOrFail($id);
        $data = [];
        if ($request->has('name')) {
            $data['name'] = $request->input('name');
        }
        if ($request->has('is_main')) {
            // Đảm bảo chỉ có 1 ảnh chính cho mỗi sản phẩm
            Image::where('product_id', $image->product_id)->update(['is_main' => false]);
            $data['is_main'] = true;
        } else {
            $data['is_main'] = false;
        }
        $image->update($data);
        return back()->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        $image = Image::findOrFail($id);
        Storage::disk('public')->delete($image->path);
        $image->delete();
        return back()->with('success', 'Đã xoá ảnh!');
    }
}
