@extends('layouts.admin')

@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold main-text">Tổng quan</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="#" style="color: #B71C1C;">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="rounded p-3" style="background-color: #FFEBEE; color: #B71C1C;">
                    <h3 class="fw-bold">{{ $products }}</h3>
                    <p>Tổng số sản phẩm</p>
                    <a href="{{ route('admin.products.index') }}"
                        class="text-decoration-none d-flex justify-content-between align-items-center text-dark fw-semibold">
                        Xem thêm <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="rounded p-3" style="background-color: #FFEBEE; color: #B71C1C;">
                    <h3 class="fw-bold">{{ $users }}</h3>
                    <p>Tổng số khách hàng</p>
                    <a href="{{ route('admin.users.index') }}"
                        class="text-decoration-none d-flex justify-content-between align-items-center text-dark fw-semibold">
                        Xem thêm <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="rounded p-3" style="background-color: #FFEBEE; color: #B71C1C;">
                    <h3 class="fw-bold">{{ $orders }}</h3>
                    <p>Tổng số đơn hàng</p>
                    <a href="{{ route('admin.orders.index') }}"
                        class="text-decoration-none d-flex justify-content-between align-items-center text-dark fw-semibold">
                        Xem thêm <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
            </div>

            <div class="col-md-3">
                <div class="rounded p-3" style="background-color: #E75480; color: white;">
                    <h4 class="fw-bold">{{ number_format($revenue, 0, ',', '.') }}₫</h4>
                    <p>Tổng doanh thu</p>
                    <a href="{{ route('admin.orders.index') }}"
                        class=" text-decoration-none d-flex justify-content-between align-items-center text-dark fw-semibold">
                        Xem thêm <i class="bi bi-arrow-right-circle-fill"></i>
                    </a>
                </div>
            </div>
        </div>

        <h5 class="mt-5 mb-3 text-center fw-bold text-danger">10 đơn hàng gần nhất</h5>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle text-center">
                <thead class="table-primary">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Tổng thanh toán</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($latestOrders as $order)
                        <tr>
                            <td><strong>{{ $order->code }}</strong></td>
                            <td>{{ $order->user->full_name ?? 'Không rõ' }}</td>
                            <td class=" fw-semibold">{{ number_format($order->final_price, 0, ',', '.') }}₫
                            </td>
                            <td>
                                <span class="status-label status-{{ $order->status }}">
                                    {{ ucfirst($order->status_text) }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-muted">Chưa có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
