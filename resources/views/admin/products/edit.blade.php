{{-- filepath: resources/views/admin/products/edit.blade.php --}}
@extends('layouts.admin')

@section('content')
    <div class="container my-4">
        <div class="row">
            <div class="col">
                <h3 class="fw-bold">Sửa sản phẩm / {{ $product->name }}</h3>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card-pink p-3">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.products.update', $product->id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-4">
                        <!-- Cột trái -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Tên sản phẩm</label>
                                <input type="text" class="form-input" name="name"
                                    value="{{ old('name', $product->name) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Danh mục</label>
                                <select class="form-select" name="category_id" required>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ $cat->id == old('category_id', $product->category_id) ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giá</label>
                                <input type="text" class="form-input" name="price"
                                    value="{{ old('price', $product->price) }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số lượng</label>
                                <input type="number" class="form-input" name="quantity"
                                    value="{{ old('quantity', $product->quantity) }}" required>
                            </div>

                        </div>
                        <!-- Cột phải -->
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-input" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="color" class="form-label">Màu áo</label>
                                <input type="text" name="color" class="form-input"
                                    value="{{ old('color', $product->color ?? '') }}">
                                @error('color')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kích cỡ</label><br>
                                @foreach (['S', 'M', 'L', 'XL'] as $size)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="sizes[]"
                                            value="{{ $size }}" id="size_{{ $size }}"
                                            {{ is_array(old('sizes', $product->sizes ?? [])) && in_array($size, old('sizes', $product->sizes ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label"
                                            for="size_{{ $size }}">{{ $size }}</label>
                                    </div>
                                @endforeach
                                @error('sizes')
                                    <br><small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- 4 ảnh trong 1 div, chia 2 hàng -->
                    <div class="card shadow-sm border-0 rounded-4 p-4 bg-white my-4">
                        <h5 class="mb-3 text-primary-pink fw-bold">Ảnh sản phẩm</h5>

                        <div class="row g-4">
                            @foreach ($product->images->take(4) as $image)
                                <div class="col-md-6 text-center">
                                    {{-- Ảnh hiện tại --}}
                                    <img src="{{ asset($image->path) }}"
                                        class="img-fluid border border-pink rounded shadow-sm mb-2" alt="Ảnh hiện tại"
                                        style="height: 150px; object-fit: cover;">

                                    {{-- Chọn ảnh thay thế --}}
                                    <div class="mb-2">
                                        <input type="file" name="replace_images[{{ $image->id }}]"
                                            class="form-input custom-file" accept="image/*">
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if ($product->images->count() < 4)
                            <div class="mt-4">
                                <label class="form-label">Tải thêm ảnh mới
                                    <span class="text-muted">(tối đa {{ 4 - $product->images->count() }} ảnh)</span>
                                </label>
                                <input type="file" name="images[]" class="form-input custom-file" accept="image/*"
                                    multiple>
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-save">Lưu sản phẩm</button>
                </form>

            </div>

        </div>

    </div>
@endsection
