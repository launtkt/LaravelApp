{{-- filepath: resources/views/admin/images/index.blade.php --}}
@extends('layouts.admin')
@section('content')
<div class="container my-4">
    <h3>Quản lý ảnh sản phẩm</h3>
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Ảnh</th>
                    <th>Tên file</th>
                    <th>Đường dẫn</th>
                    <th>Tên ảnh (admin tự sửa)</th>
                    <th>Ảnh chính</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($images as $image)
                <tr>
                    <td>{{ $image->product->name ?? '---' }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $image->path) }}" class="img-thumbnail" style="height: 50px; width: 50px;" alt="{{ $image->name }}">
                    </td>
                    <td>{{ basename($image->path) }}</td>
                    <td>{{ $image->path }}</td>
                    <td>
                        <form action="{{ route('admin.images.update', $image->id) }}" method="POST" class="d-flex align-items-center">
                            @csrf
                            @method('PUT')
                            <input type="text" name="name" value="{{ $image->name }}" class="form-control form-control-sm me-2" style="width:120px;" placeholder="Nhập tên ảnh">
                            <button class="btn btn-sm btn-success" type="submit" title="Lưu tên"><i class="bi bi-save"></i></button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.images.update', $image->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="name" value="{{ $image->name }}">
                            <input type="checkbox" name="is_main" value="1" {{ $image->is_main ? 'checked' : '' }} onchange="this.form.submit()">
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('admin.images.destroy', $image->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-dark" onclick="return confirm('Bạn có chắc muốn xoá?')" title="Xoá">
                                <i class="bi bi-trash2-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if($images->isEmpty())
                <tr>
                    <td colspan="7">Không có ảnh nào.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
