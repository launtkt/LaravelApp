@extends('layouts.admin')
@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold main-text">Danh sách ảnh sản phẩm</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Quản lý sản phẩm</li>
                    <li class="breadcrumb-item active" aria-current="page">Danh sách ảnh sản phẩm</li>
                </ol>
            </nav>
        </div>
        <form method="GET" action="{{ route('admin.images.index') }}">
            <div class="row g-3 align-items-end mb-4">
                <!-- Tìm kiếm theo tên sản phẩm -->
                <div class="col-md-4">
                    <div class="d-flex">
                        <input type="text" id="search" name="search" class="form-input rounded-start flex-grow-1"
                            style="min-width: 0;"
                            value="{{ request('search') }}" placeholder="Nhập tên sản phẩm">
                        <button class="btn btn-tim rounded-end flex-shrink-0 ms-1" type="submit">Tìm</button>
                    </div>
                </div>

                <!-- Lọc theo sản phẩm -->
                <!-- Lọc theo sản phẩm -->
                <div class="col-md-4">
                    <div class="custom-dropdown">
                        <!-- Nút hiển thị sản phẩm được chọn -->
                        <button type="button" class="dropdown-toggle" onclick="toggleDropdown()" id="selectedProduct">
                            {{ $products->firstWhere('id', request('product_id'))?->name ?? 'Tất cả sản phẩm' }}
                        </button>

                        <!-- Danh sách sản phẩm -->
                        <div id="dropdownList" class="dropdown-menu">
                            <div class="dropdown-item" onclick="selectProduct('', 'Tất cả sản phẩm')">Tất cả sản phẩm</div>
                            @foreach ($products as $product)
                                <div class="dropdown-item {{ request('product_id') == $product->id ? 'active' : '' }}"
                                    onclick="selectProduct('{{ $product->id }}', '{{ $product->name }}')">
                                    {{ $product->name }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>



                <!-- Nút thêm ảnh sản phẩm -->
                <div class="col-md-4 text-end">
                    <a href="{{ route('admin.images.create') }}" class="btn btn-add w-100">
                        Thêm ảnh sản phẩm mới
                    </a>
                </div>
            </div>
        </form>


        {{-- @if (session('success')) @class(['mt-2', 'font-bold' => true])

            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}
        <div class="table-responsive mt-3">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Ảnh</th>
                        <th>Sản phẩm</th>
                        <th>Ảnh chính</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($images as $image)
                        <tr>
                            <td>
                                <img src="{{ asset($image->path) }}" width="50" alt="Ảnh sản phẩm">
                            </td>
                            <td>{{ $image->product->name ?? 'Không xác định' }}</td>
                            <td>
                                @if ($image->is_main)
                                    <span class="badge bg-success">Chính</span>
                                @else
                                    <span class="badge bg-secondary">Phụ</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.images.edit', $image->id) }}"
                                        class="btn btn-sm btn-edit" title="Sửa">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.images.destroy', $image->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn xoá?')" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" title="Xoá">
                                            <i class="bi bi-trash2-fill"></i>
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted">Không có ảnh nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>




        <div class="mt-3">
            {{ $images->links() }}
        </div>
    </div>
    <script>
        // Mở hoặc đóng dropdown
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownList');
            dropdown.classList.toggle('show');
        }

        // Khi chọn sản phẩm
        function selectProduct(productId, productName) {
            document.getElementById('selectedProduct').innerText = productName;

            // Đóng dropdown
            document.getElementById('dropdownList').classList.remove('show');

            // Tạo URL mới với tham số product_id
            const url = new URL(window.location.href);
            if (productId) {
                url.searchParams.set('product_id', productId);
            } else {
                url.searchParams.delete('product_id');
            }

            // Điều hướng tới URL mới → tự động load lại
            window.location.href = url.toString();
        }

        // Đóng dropdown nếu click ra ngoài
        window.addEventListener('click', function(e) {
            const toggleBtn = document.getElementById('selectedProduct');
            const dropdown = document.getElementById('dropdownList');
            if (!toggleBtn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
@endsection
