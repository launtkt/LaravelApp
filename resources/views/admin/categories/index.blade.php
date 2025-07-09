{{-- filepath: resources/views/admin/categories/index.blade.php --}}
@extends('layouts.admin')
@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0"style="color: #B71C1C;">Danh sách danh mục</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Quản lý danh mục</li>
                    <li class="breadcrumb-item active" aria-current="page">Danh sách danh mục</li>
                </ol>
            </nav>
        </div>

        <form method="GET" action="{{ route('admin.categories.index') }}">
            <div class="row d-flex align-items-center mb-4">
                <!-- Div chứa input + button -->
                <div class="col-12 col-md-4">
                    <div class="d-flex">
                        <input type="text" name="search" class="form-input rounded-start flex-grow-1"
                            style="min-width: 0;" value="{{ request('search') }}" placeholder="Tìm kiếm danh mục">
                        <button type="submit" class="btn btn-tim rounded-end flex-shrink-0 ms-1">
                            Tìm
                        </button>
                    </div>
                </div>



                <!-- Div chứa select option sắp xếp -->
                <div class="col-12 col-md-3">
                    <select name="sort" class="form-select" onchange="this.form.submit()">
                        {{-- <option value="">Sắp xếp</option> --}}
                        <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Mặc định</option>
                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Mới nhất</option>
                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Cũ nhất</option>
                    </select>
                </div>
                {{-- // Div chứa select option trạng thái --}}
                <div class="col-12 col-md-2">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Đã khoá</option>
                    </select>
                </div>

                <!-- Div chứa nút Thêm danh mục -->
                <div class="col-12 col-md-3 ms-auto">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-add w-100">Thêm danh mục mới</a>
                </div>
            </div>
        </form>
        <div class="table-responsive mt-3">
            <table class="table table-hover table-bordered align-middle text-center table-pink">
                <thead class="table-primary">
                    <tr>
                        <th scope="col">Tên danh mục</th>
                        <th scope="col">Mô tả</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($categories as $cat)
                        <tr>
                            <td>{{ $cat->name }}</td>
                            <td>{{ $cat->description }}</td>
                            <td>
                                @if ($cat->status)
                                    <span class="status-active">Hoạt động</span>
                                @else
                                    <span class="status-locked">Đã khoá</span>
                                @endif
                            </td>
                            <td>{{ $cat->created_at->format('d/m/Y') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Sửa --}}
                                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn-action btn-edit"
                                        title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Khoá / Mở khoá --}}
                                    @if ($cat->status)
                                        <form action="{{ route('admin.categories.lock', $cat->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn khoá danh mục này?')">
                                            @csrf
                                            <button class="btn-action btn-lock" title="Khoá">
                                                <i class="bi bi-lock"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.categories.unlock', $cat->id) }}" method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn mở khoá danh mục này?')">
                                            @csrf
                                            <button class="btn-action btn-unlock" title="Mở khoá">
                                                <i class="bi bi-unlock"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">Không có danh mục nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $categories->links() }}
    </div>
@endsection
