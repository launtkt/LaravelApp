@extends('layouts.admin')
@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold main-text">Danh sách sản phẩm</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Quản lý sản phẩm</li>
                    <li class="breadcrumb-item active" aria-current="page">Danh sách sản phẩm</li>
                </ol>
            </nav>
        </div>
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3 mb-3">
            {{-- Ô tìm kiếm --}}
            <div class="col-md-3">
                <div class="d-flex">
                    <input type="text" name="search" class="form-input rounded-start flex-grow-1" style="min-width: 0;"
                        placeholder="Nhập tên hoặc mã sản phẩm" value="{{ request('search') }}">
                    <button class="btn btn-tim rounded-end flex-shrink-0 ms-1" type="submit">Tìm</button>
                </div>
            </div>

            {{-- Lọc theo danh mục --}}
            <div class="col-md-2">
                <select name="category_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Tất cả danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Lọc theo trạng thái hàng --}}
            <div class="col-md-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Tất cả trạng thái</option>
                    <option value="in" {{ request('status') == 'in' ? 'selected' : '' }}>Còn hàng</option>
                    <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Sắp hết hàng
                    </option>
                    <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Hết hàng</option>
                </select>
            </div>

            {{-- Lọc theo trạng thái hoạt động --}}
            <div class="col-md-3">
                <select name="is_active" class="form-select" onchange="this.form.submit()">
                    <option value="">Trạng thái hoạt động</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Đã khoá</option>
                </select>
            </div>

            {{-- Nút thêm sản phẩm --}}
            <div class="col-md-2 ms-auto">
                <a href="{{ route('admin.products.create') }}" class="btn btn-add w-100">Thêm sản phẩm mới</a>
            </div>
        </form>


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive mt-4">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Ảnh chính</th>
                        <th>Sản phẩm</th>
                        <th>Mã sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>
                                @php
                                    $mainImage =
                                        $product->images->where('is_main', true)->first() ?? $product->images->first();
                                @endphp
                                @if ($mainImage)
                                    <img src="{{ asset($mainImage->path) }}" alt="{{ $product->name }}" width="50"
                                        class="rounded">
                                @else
                                    <span class="text-muted">Chưa có ảnh</span>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->category->name ?? 'Chưa phân loại' }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                <span class="status-label {{ $product->status_class }}">
                                    {{ $product->status_text }}
                                </span>
                            </td>

                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-view"
                                        title="Xem">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-edit"
                                        title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if ($product->is_active)
                                        <form action="{{ route('admin.products.lock', $product->id) }}" method="POST"
                                            onsubmit="return confirm('Khoá sản phẩm này?')" style="display: inline-block;">
                                            @csrf
                                            <button class="btn btn-sm btn-lock" title="Khoá sản phẩm">
                                                <i class="bi bi-lock"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.products.unlock', $product->id) }}" method="POST"
                                            onsubmit="return confirm('Mở khoá sản phẩm này?')"
                                            style="display: inline-block;">
                                            @csrf
                                            <button class="btn btn-sm btn-unlock" title="Mở khoá">
                                                <i class="bi bi-unlock"></i>
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted">Không có sản phẩm nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $products->links() }}
        </div>
    </div>
@endsection
