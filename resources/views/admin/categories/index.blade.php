{{-- filepath: resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')
@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Danh sách danh mục</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Quản lý danh mục</li>
                <li class="breadcrumb-item active" aria-current="page">Danh sách danh mục</li>
            </ol>
        </nav>
    </div>
    <form method="GET" action="{{ route('admin.categories.index') }}">
        <div class="row justify-content-between">
            <div class="col-12 col-md-4">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tìm kiếm danh mục">
                    <button class="btn btn-secondary border" type="submit">Tìm kiếm</button>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary w-100">Thêm danh mục mới</a>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>Tên danh mục</th>
                    <th>Mô tả</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        @if($category->status)
                            <span class="badge bg-success">Hoạt động</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-outline-dark me-1" title="Xem"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-outline-dark me-1" title="Sửa"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-dark" onclick="return confirm('Bạn có chắc muốn xoá?')" title="Xoá">
                                <i class="bi bi-trash2-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4">Không có danh mục nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $categories->links() }}
    </div>
</div>
@endsection
