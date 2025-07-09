{{-- filepath: resources/views/admin/categories/create.blade.php --}}
@extends('layouts.admin')
@section('content')
    <div class="container my-4">
        <h3>Thêm danh mục</h3>
        <div class="card-pink p-3">

            {{-- Thông báo lỗi --}}
            <div class="card-body">
                <form method="POST" action="{{ route('admin.categories.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tên danh mục </label>
                        <input type="text" name="name" class="form-input" required value="{{ old('name') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-input">{{ old('description') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-save">Lưu</button>
                </form>
            </div>

        </div>

    </div>
@endsection
