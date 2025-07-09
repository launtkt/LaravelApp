{{-- filepath: resources/views/admin/products/create.blade.php --}}
@extends('layouts.admin')

@section('content')
<div class="container my-4">
    <h3>Thêm sản phẩm mới</h3>
    <div class="card-pink p-3">

        {{-- Thông báo lỗi --}}
        <div class="card-body">
           <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <!-- Cột trái -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Tên sản phẩm</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Danh mục</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Giá</label>
                    <input type="number" name="price" class="form-input" value="{{ old('price') }}" required>
                    @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Số lượng</label>
                    <input type="number" name="quantity" class="form-input" value="{{ old('quantity') }}" required>
                    @error('quantity') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <!-- Cột phải -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Mô tả</label>
                    <textarea class="form-input" name="description" rows="3">{{ old('description') }}</textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Màu sắc</label>
                    <input type="text" name="color" class="form-input" value="{{ old('color') }}">
                    @error('color') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Kích cỡ</label><br>
                    @foreach(['S','M','L','XL'] as $size)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="sizes[]" value="{{ $size }}" id="size_{{ $size }}"
                                {{ is_array(old('sizes')) && in_array($size, old('sizes')) ? 'checked' : '' }}>
                            <label class="form-check-label" for="size_{{ $size }}">{{ $size }}</label>
                        </div>
                    @endforeach
                    @error('sizes') <br><small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>
        <!-- Chọn tối đa 4 ảnh, mỗi ảnh 1 input, chia 2 hàng 2 cột -->
        <div class="image-upload bg-white border rounded p-3 my-4">
            <label class="form-label">Chọn tối đa 4 ảnh sản phẩm</label>
            <div class="row">
                <div class="col-6 mb-2">
                    <input type="file" name="images[]" class="form-input custom-file" accept="image/*" required>
                    @error('images.0') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-6 mb-2">
                    <input type="file" name="images[]" class="form-input custom-file" accept="image/*">
                    @error('images.1') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-6">
                    <input type="file" name="images[]" class="form-input custom-file" accept="image/*">
                    @error('images.2') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
                <div class="col-6">
                    <input type="file" name="images[]" class="form-input custom-file" accept="image/*">
                    @error('images.3') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            @error('images') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <button type="submit" class="btn btn-add">Thêm sản phẩm</button>
    </form>
        </div>
    </div>

</div>
@endsection
