{{-- filepath: resources/views/admin/products/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container my-4">
    <div class="row mb-3">
        <div class="col">
            <h4 class="fw-bold">Sửa sản phẩm / {{ $product->name }}</h4>
        </div>
        <div class="col text-end text-secondary">
            Home / Sản phẩm / <span class="fw-bold text-dark">Sửa sản phẩm</span>
        </div>
    </div>
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('admin.products.update', $product->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-4">
            <!-- Cột trái -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" class="form-control" name="name" value="{{ old('name', $product->name) }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Giá</label>
                    <input type="text" class="form-control" name="price" value="{{ old('price', $product->price) }}" required>
                </div>
                {{-- <div class="mb-3">
                    <label class="form-label">Mã sản phẩm</label>
                    <input type="text" class="form-control" name="product_code" value="{{ old('product_code', $product->product_code) }}" required>
                </div> --}}
                <div class="mb-3">
                    <label class="form-label">Số lượng</label>
                    <input type="number" class="form-control" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                </div>
            </div>
            <!-- Cột phải -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Danh mục</label>
                    <select class="form-select" name="category_id" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $cat->id == old('category_id', $product->category_id) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-control" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="color" class="form-label">Màu áo</label>
                    <input type="text" name="color" class="form-control" value="{{ old('color', $product->color ?? '') }}">
                    @error('color') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Kích cỡ</label><br>
                    @foreach(['S','M','L','XL'] as $size)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size }}" id="size_{{ $size }}"
                                {{ (is_array(old('sizes', $product->sizes ?? [])) && in_array($size, old('sizes', $product->sizes ?? []))) ? 'checked' : '' }}>
                            <label class="form-check-label" for="size_{{ $size }}">{{ $size }}</label>
                        </div>
                    @endforeach
                    @error('sizes') <br><small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>
        <!-- 4 ảnh trong 1 div, chia 2 hàng -->
        <div class="border rounded p-3 bg-light my-4">
            <div class="row g-3">
                @for($i = 1; $i <= 4; $i++)
                    <div class="col-md-6">
                        <label for="image{{ $i }}" class="form-label">Ảnh {{ $i }}</label>
                        <input type="file" name="image{{ $i }}" class="form-control" accept="image/*">
                        @error('image'.$i) <small class="text-danger">{{ $message }}</small> @enderror
                        @if(isset($product) && $product->{'image'.$i})
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $product->{'image'.$i}) }}" class="img-thumbnail" style="max-height: 180px;">
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>
        <button type="submit" class="btn btn-info px-5">Lưu sản phẩm</button>
    </form>
</div>
@endsection
