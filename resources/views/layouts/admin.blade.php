{{-- filepath: resources/views/layouts/admin.blade.php --}}
@php
    $dashboardActive = request()->routeIs('admin.dashboard');
    $productActive = request()->routeIs('admin.products.*');
    $accountActive = request()->routeIs('admin.accounts.*');
    $newsActive = request()->routeIs('admin.news.*');
    $categoryActive = request()->routeIs('admin.categories.*');
    $orderActive = request()->routeIs('admin.orders.*');
    $customerActive = request()->routeIs('admin.customers.*');
@endphp

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Layout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-dark sidebar p-0">
                <div class="d-flex align-items-center justify-content-center p-3">
                    <span class="fs-5 fw-bold text-white">Admin</span>
                </div>
                <hr class="border-light m-0">
                <div class="accordion bg-dark" id="sidebarMenu">
                    <!-- Tổng quan -->
                    <div class="accordion-item bg-dark border border-secondary rounded mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white py-2 text-truncate w-100{{ $dashboardActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseDashboard"
                                aria-expanded="{{ $dashboardActive ? 'true' : 'false' }}">
                                <span title="Tổng quan">🏠 Tổng quan</span>
                            </button>
                        </h2>
                        <div id="collapseDashboard" class="accordion-collapse collapse{{ $dashboardActive ? ' show' : '' }}" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <a href="{{ route('admin.dashboard') }}" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Trang tổng quan">
                                    Trang tổng quan
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- Quản lý sản phẩm -->
                    <div class="accordion-item bg-dark border border-secondary rounded mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white py-2 text-truncate w-100{{ $productActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseProduct"
                                aria-expanded="{{ $productActive ? 'true' : 'false' }}">
                                <span title="Quản lý sản phẩm">📦 Quản lý sản phẩm</span>
                            </button>
                        </h2>
                        <div id="collapseProduct" class="accordion-collapse collapse{{ $productActive ? ' show' : '' }}" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <a href="{{ route('admin.products.index') }}" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Danh sách sản phẩm">Danh sách sản phẩm</a>
                                <a href="{{ route('admin.images.index') }}" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Danh sách ảnh sản phẩm">Danh sách ảnh sản phẩm</a>
                                <a href="{{ route('admin.products.create') }}" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Thêm sản phẩm">Thêm sản phẩm</a>
                            </div>
                        </div>
                    </div>
                    <!-- Quản lý tài khoản -->
                    <div class="accordion-item bg-dark border border-secondary rounded mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white py-2 text-truncate w-100{{ $accountActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseAccount"
                                aria-expanded="{{ $accountActive ? 'true' : 'false' }}">
                                <span title="Quản lý tài khoản">👤 Quản lý tài khoản</span>
                            </button>
                        </h2>
                        <div id="collapseAccount" class="accordion-collapse collapse{{ $accountActive ? ' show' : '' }}" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Danh sách tài khoản">Danh sách tài khoản</a>
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Thêm tài khoản">Thêm tài khoản</a>
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Phân quyền">Phân quyền</a>
                            </div>
                        </div>
                    </div>
                    <!-- Quản lý tin tức -->
                    <div class="accordion-item bg-dark border border-secondary rounded mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white py-2 text-truncate w-100{{ $newsActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseNews"
                                aria-expanded="{{ $newsActive ? 'true' : 'false' }}">
                                <span title="Quản lý tin tức">📰 Quản lý tin tức</span>
                            </button>
                        </h2>
                        <div id="collapseNews" class="accordion-collapse collapse{{ $newsActive ? ' show' : '' }}" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Danh sách tin">Danh sách tin</a>
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Thêm bài viết">Thêm bài viết</a>
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Chuyên mục tin">Chuyên mục tin</a>
                            </div>
                        </div>
                    </div>
                    <!-- Quản lý danh mục -->
                    <div class="accordion-item bg-dark border border-secondary rounded mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white py-2 text-truncate w-100{{ $categoryActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategory"
                                aria-expanded="{{ $categoryActive ? 'true' : 'false' }}">
                                <span title="Quản lý danh mục">🗂️ Quản lý danh mục</span>
                            </button>
                        </h2>
                        <div id="collapseCategory" class="accordion-collapse collapse{{ $categoryActive ? ' show' : '' }}" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <a href="{{ route('admin.categories.index') }}" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Danh sách danh mục">Danh sách danh mục</a>
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Thêm danh mục">Thêm danh mục</a>
                            </div>
                        </div>
                    </div>
                    <!-- Quản lý đơn hàng -->
                    <div class="accordion-item bg-dark border border-secondary rounded mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white py-2 text-truncate w-100{{ $orderActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrder"
                                aria-expanded="{{ $orderActive ? 'true' : 'false' }}">
                                <span title="Quản lý đơn hàng">🛒 Quản lý đơn hàng</span>
                            </button>
                        </h2>
                        <div id="collapseOrder" class="accordion-collapse collapse{{ $orderActive ? ' show' : '' }}" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <a href="{{ route('admin.orders.index') }}" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Danh sách đơn hàng">Danh sách đơn hàng</a>
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Thêm đơn hàng">Thêm đơn hàng</a>
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Trạng thái đơn hàng">Trạng thái đơn hàng</a>
                            </div>
                        </div>
                    </div>
                    <!-- Quản lý voucher -->
                    <div class="accordion-item bg-dark border border-secondary rounded mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white py-2 text-truncate w-100"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseVoucher">
                                <span title="Quản lý voucher">🎟️ Quản lý voucher</span>
                            </button>
                        </h2>
                        <div id="collapseVoucher" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Danh sách voucher">Danh sách voucher</a>
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Thêm voucher">Thêm voucher</a>
                            </div>
                        </div>
                    </div>
                    <!-- Quản lý bình luận/đánh giá -->
                    <div class="accordion-item bg-dark border border-secondary rounded mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white py-2 text-truncate w-100"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseReview">
                                <span title="Quản lý bình luận/đánh giá">💬 Quản lý bình luận/đánh giá</span>
                            </button>
                        </h2>
                        <div id="collapseReview" class="accordion-collapse collapse" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Danh sách bình luận">Danh sách bình luận</a>
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Thêm bình luận">Thêm bình luận</a>
                            </div>
                        </div>
                    </div>
                    <!-- Quản lý khách hàng -->
                    <div class="accordion-item bg-dark border border-secondary rounded mb-2">
                        <h2 class="accordion-header">
                            <button class="accordion-button bg-dark text-white py-2 text-truncate w-100{{ $customerActive ? '' : ' collapsed' }}"
                                type="button" data-bs-toggle="collapse" data-bs-target="#collapseCustomer"
                                aria-expanded="{{ $customerActive ? 'true' : 'false' }}">
                                <span title="Quản lý khách hàng">🧑‍💼 Quản lý khách hàng</span>
                            </button>
                        </h2>
                        <div id="collapseCustomer" class="accordion-collapse collapse{{ $customerActive ? ' show' : '' }}" data-bs-parent="#sidebarMenu">
                            <div class="accordion-body p-0">
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Danh sách khách hàng">Danh sách khách hàng</a>
                                <a href="#" class="d-block text-white py-2 px-4 text-decoration-none text-truncate w-100" title="Thêm khách hàng">Thêm khách hàng</a>
                            </div>
                        </div>
                    </div>
                    @yield('sidebar')
                </div>
            </nav>
            <!-- Main content -->
            <div class="col-md-10 ms-sm-auto d-flex flex-column p-0">
                <!-- Topbar -->
                <header class="border-bottom d-flex justify-content-between align-items-center px-4 py-2 bg-white">
                    <button type="button" class="btn btn-primary" onclick="window.history.back();">
                        Quay lại
                    </button>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle fs-4 me-2"></i>
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
                <footer class="text-center text-white bg-secondary py-3 small m-0">
                    &copy; 2025 Admin Panel. All rights reserved.
                </footer>
            </div>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
