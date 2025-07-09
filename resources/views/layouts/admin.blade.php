@php
    $dashboardActive = request()->routeIs('admin.dashboard');
    $productActive = request()->routeIs('admin.products.*') || request()->routeIs('admin.images.*');
    $userActive = request()->routeIs('admin.users.*');
    $voucherActive = request()->routeIs('admin.vouchers.*');
    $categoryActive = request()->routeIs('admin.categories.*');
    $orderActive = request()->routeIs('admin.orders.*');
    $commentActive = request()->routeIs('admin.comments.*');
    $reviewActive = request()->routeIs('admin.reviews.*');
@endphp

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel | Mova</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/content.css') }}">
    <!-- CSS Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- JS Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Custom màu theo logo -->

</head>

<body>
    <div class="container-fluid">
        <div class="d-flex min-vh-100">
            <!-- Sidebar -->
            <nav class="d-none d-md-block sidebar p-0" style="min-width: 250px;">
                <div class="text-center py-3" style="background-color: var(--background-color);">
                    <img src="{{ asset('storage/avatars/logo1.png') }}" alt="Logo" style="height: 90px;">
                </div>
                <hr class="border-light m-0">
                <div class="accordion" id="sidebarMenu">

                    <!-- Tổng quan -->
                    <div class="accordion-item rounded mb-2">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button py-2 text-truncate w-100{{ $dashboardActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseDashboard"
                                aria-expanded="{{ $dashboardActive ? 'true' : 'false' }}">
                                🏠 Tổng quan
                            </button>
                        </h2>
                        <div id="collapseDashboard"
                            class="accordion-collapse collapse{{ $dashboardActive ? ' show' : '' }}"
                            data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <a href="{{ route('admin.dashboard') }}"
                                    class="d-block py-2 px-4 text-decoration-none w-100 {{ request()->routeIs('admin.dashboard') ? 'active-link' : '' }}">
                                    Tổng quan
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Danh mục -->
                    <div class="accordion-item rounded mb-2">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button py-2 text-truncate w-100{{ $categoryActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategory"
                                aria-expanded="{{ $categoryActive ? 'true' : 'false' }}">
                                🗂️ Quản lý danh mục
                            </button>
                        </h2>
                        <div id="collapseCategory"
                            class="accordion-collapse collapse{{ $categoryActive ? ' show' : '' }}"
                            data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.categories.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.categories.index') ? 'active' : '' }}">
                                            Danh sách danh mục
                                        </a>
                                    </li>

                                    @if (request()->routeIs('admin.categories.create'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.categories.create') }}"
                                                class="nav-link {{ request()->routeIs('admin.categories.create') ? 'active' : '' }}">
                                                Thêm danh mục
                                            </a>
                                        </li>
                                    @endif
                                    @if (request()->routeIs('admin.categories.edit') && request()->route('category'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.categories.edit', request()->route('category')) }}"
                                                class="nav-link active">
                                                Sửa danh mục
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Sản phẩm -->
                    <div class="accordion-item rounded mb-2">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button py-2 text-truncate w-100{{ $productActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseProduct"
                                aria-expanded="{{ $productActive ? 'true' : 'false' }}">
                                📦 Quản lý sản phẩm
                            </button>
                        </h2>
                        <div id="collapseProduct"
                            class="accordion-collapse collapse{{ $productActive ? ' show' : '' }}"
                            data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.products.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.products.index') ? 'active' : '' }}">
                                            Danh sách sản phẩm
                                        </a>
                                    </li>

                                    @if (request()->routeIs('admin.products.create'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.products.create') }}"
                                                class="nav-link {{ request()->routeIs('admin.products.create') ? 'active' : '' }}">
                                                Thêm sản phẩm
                                            </a>
                                        </li>
                                    @endif
                                    @if (request()->routeIs('admin.products.edit') && request()->route('product'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.products.edit', request()->route('product')) }}"
                                                class="nav-link active">
                                                Sửa sản phẩm
                                            </a>
                                        </li>
                                    @endif
                                    @if (request()->routeIs('admin.products.show') && request()->route('product'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.products.show', request()->route('product')) }}"
                                                class="nav-link active">
                                                Chi tiết sản phẩm
                                            </a>
                                        </li>
                                    @endif
                                    <li class="nav-item">
                                        <a href="{{ route('admin.images.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.images.index') ? 'active' : '' }}">
                                            Danh sách ảnh sản phẩm
                                        </a>
                                    </li>

                                    @if (request()->routeIs('admin.images.create'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.images.create') }}"
                                                class="nav-link {{ request()->routeIs('admin.images.create') ? 'active' : '' }}">
                                                Thêm ảnh sản phẩm
                                            </a>
                                        </li>
                                    @endif
                                    @if (request()->routeIs('admin.images.edit') && request()->route('image'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.images.edit', request()->route('image')) }}"
                                                class="nav-link active">
                                                Sửa ảnh sản phẩm
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Người dùng -->
                    <div class="accordion-item rounded mb-2">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button py-2 text-truncate w-100{{ $userActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseUser"
                                aria-expanded="{{ $userActive ? 'true' : 'false' }}">
                                👤 Quản lý người dùng
                            </button>
                        </h2>
                        <div id="collapseUser" class="accordion-collapse collapse{{ $userActive ? ' show' : '' }}"
                            data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.users.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.users.index') ? 'active' : '' }}">
                                            Danh sách người dùng
                                        </a>
                                    </li>

                                    @if (request()->routeIs('admin.users.create'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.users.create') }}"
                                                class="nav-link {{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                                                Thêm Admin
                                            </a>
                                        </li>
                                    @endif
                                    @if (request()->routeIs('admin.users.show') && request()->route('user'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.users.show', request()->route('user')) }}"
                                                class="nav-link active">
                                                Chi tiết người dùng
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Voucher -->
                    <div class="accordion-item rounded mb-2">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button py-2 text-truncate w-100{{ $voucherActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseVoucher"
                                aria-expanded="{{ $voucherActive ? 'true' : 'false' }}">
                                🎁 Quản lý Voucher
                            </button>
                        </h2>
                        <div id="collapseVoucher"
                            class="accordion-collapse collapse{{ $voucherActive ? ' show' : '' }}"
                            data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.vouchers.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.vouchers.index') ? 'active' : '' }}">
                                            Danh sách voucher
                                        </a>
                                    </li>

                                    @if (request()->routeIs('admin.vouchers.create'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.vouchers.create') }}"
                                                class="nav-link {{ request()->routeIs('admin.vouchers.create') ? 'active' : '' }}">
                                                Thêm voucher
                                            </a>
                                        </li>
                                    @endif
                                    @if (request()->routeIs('admin.vouchers.edit') && request()->route('voucher'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.vouchers.edit', request()->route('voucher')) }}"
                                                class="nav-link active">
                                                Sửa voucher
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Đơn hàng -->
                    <div class="accordion-item rounded mb-2">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button py-2 text-truncate w-100{{ $orderActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrder"
                                aria-expanded="{{ $orderActive ? 'true' : 'false' }}">
                                🛒 Quản lý đơn hàng
                            </button>
                        </h2>
                        <div id="collapseOrder" class="accordion-collapse collapse{{ $orderActive ? ' show' : '' }}"
                            data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.orders.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.orders.index') ? 'active' : '' }}">
                                            Danh sách đơn hàng
                                        </a>
                                    </li>

                                    @if (request()->routeIs('admin.orders.create'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.orders.create') }}"
                                                class="nav-link {{ request()->routeIs('admin.orders.create') ? 'active' : '' }}">
                                                Thêm đơn hàng
                                            </a>
                                        </li>
                                    @endif
                                    @if (request()->routeIs('admin.orders.edit') && request()->route('order'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.orders.edit', request()->route('order')) }}"
                                                class="nav-link active">
                                                Sửa đơn hàng
                                            </a>
                                        </li>
                                    @endif
                                    @if (request()->routeIs('admin.orders.show') && request()->route('order'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.orders.show', request()->route('order')) }}"
                                                class="nav-link active">
                                                Chi tiết đơn hàng
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Bình luận & đánh giá -->
                    <div class="accordion-item rounded mb-2">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button py-2 w-100{{ $commentActive || $reviewActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseReview"
                                aria-expanded="{{ $commentActive || $reviewActive ? 'true' : 'false' }}">
                                💬 Bình luận & Đánh giá
                            </button>
                        </h2>
                        <div id="collapseReview"
                            class="accordion-collapse collapse{{ $commentActive || $reviewActive ? ' show' : '' }}"
                            data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.comments.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.comments.index') ? 'active' : '' }}">
                                            Danh sách bình luận
                                        </a>
                                    </li>
                                    @if (request()->routeIs('admin.comments.edit') && request()->route('comment'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.comments.edit', request()->route('comment')) }}"
                                                class="nav-link active">
                                                Sửa bình luận
                                            </a>
                                        </li>
                                    @endif

                                </ul>
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.reviews.index') }}"
                                            class="nav-link {{ request()->routeIs('admin.reviews.index') ? 'active' : '' }}">
                                            Danh sách đánh giá
                                        </a>
                                    </li>
                                    @if (request()->routeIs('admin.reviews.edit') && request()->route('review'))
                                        <li class="nav-item acor-con">
                                            <a href="{{ route('admin.reviews.edit', request()->route('review')) }}"
                                                class="nav-link active">
                                                Sửa đánh giá
                                            </a>
                                        </li>
                                    @endif
                            </div>
                        </div>
                    </div>

                    @yield('sidebar')
                </div>
            </nav>

            <!-- Main content -->
            <div class="flex-grow-1 ms-sm-auto d-flex flex-column p-0">
                <!-- Topbar -->
                <header class="border-bottom d-flex justify-content-between align-items-center px-4 py-2 bg-white">
                    <div>
                        
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown" style="color: #E75480;">
                            <i class="bi bi-person-circle fs-4 me-2" style="color: #E75480;"></i>
                            <span>Admin</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Thông tin tài khoản</a></li>
                            <li><a class="dropdown-item" href="#">Cài đặt</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="#">Đăng xuất</a></li>
                        </ul>
                    </div>
                </header>

                <!-- Main -->
                <main class="flex-grow-1 p-4 bg-light">
                    @yield('content')
                </main>

                <!-- Footer -->
                <footer class="text-center py-3 small m-0">
                    &copy; 2025 Mova Admin Panel. All rights reserved.
                </footer>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
