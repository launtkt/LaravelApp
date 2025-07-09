@extends('layouts.admin')
@section('content')
    <div class="container my-4">
        <h3>Sửa ảnh sản phẩm</h3>
        <div class="card-pink p-3">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.images.update', $image->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Sản phẩm</label>
                        <select name="product_id" class="form-select" disabled>
                            <option value="{{ $image->product->id }}">{{ $image->product->name }}</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ảnh hiện tại</label><br>
                        <img src="{{ asset($image->path) }}" width="150" class="img-thumbnail">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chọn ảnh mới</label>
                        <input type="file" name="image" class="form-input custom-file" accept="image/*">
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-check mb-3">
                        @if ($image->is_main)
                            <input type="checkbox" class="form-check-input" id="mainCheck" checked disabled>
                            <label class="form-check-label text-muted" for="mainCheck">
                                Ảnh này đang là ảnh chính (không thể thay đổi)
                            </label>
                            <input type="hidden" name="is_main" value="1">
                        @else
                            <input type="checkbox" class="form-check-input" id="mainCheck" disabled>
                            <label class="form-check-label text-muted" for="mainCheck">
                                Đây là ảnh phụ (không thể đặt làm ảnh chính)
                            </label>
                            <input type="hidden" name="is_main" value="0">
                        @endif
                    </div>

                    <button type="submit" class="btn btn-save">Cập nhật</button>
                </form>
            </div>
        </div>
    </div>
@endsection
