{{-- filepath: resources/views/admin/orders/index.blade.php --}}
@extends('layouts.admin')
@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Danh sách đơn hàng</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Quản lý đơn hàng</li>
                <li class="breadcrumb-item active" aria-current="page">Danh sách đơn hàng</li>
            </ol>
        </nav>
    </div>
    <form method="GET" action="{{ route('admin.orders.index') }}">
        <div class="row justify-content-between">
            <div class="col-12 col-md-4">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tìm kiếm mã đơn, khách hàng...">
                    <button class="btn btn-secondary border" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>Mã đơn hàng</th>
                    <th>Khách hàng</th>
                    <th>Sản phẩm</th>
                    <th>Tổng tiền</th>
                    <th>Trạng thái đơn hàng</th>
                    <th>Ngày đặt hàng</th>
                    <th>Phương thức thanh toán</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->code ?? $order->id }}</td>
                    <td>{{ $order->customer_name ?? $order->customer->name ?? $order->customer_email ?? '---' }}</td>
                    <td>
                        @if(isset($order->products) && $order->products->count())
                            {{ $order->products->first()->name }}
                            @if($order->products->count() > 1)
                                +{{ $order->products->count() - 1 }} sản phẩm
                            @endif
                        @else
                            ---
                        @endif
                    </td>
                    <td>{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                    <td>
                        @php
                            $statusMap = [
                                'pending' => 'Chờ xác nhận',
                                'shipping' => 'Đang giao',
                                'completed' => 'Đã giao',
                                'cancelled' => 'Đã huỷ',
                                'returned' => 'Hoàn trả'
                            ];
                        @endphp
                        <span class="badge bg-{{ $order->status == 'completed' ? 'success' : ($order->status == 'pending' ? 'warning' : ($order->status == 'shipping' ? 'info' : ($order->status == 'cancelled' ? 'danger' : 'secondary'))) }}">
                            {{ $statusMap[$order->status] ?? ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $order->payment_method ?? '---' }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark me-1" title="Xem"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-dark me-1" title="Sửa"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-dark" onclick="return confirm('Bạn có chắc muốn xoá?')" title="Xoá">
                                <i class="bi bi-trash2-fill"></i>
                            </button>
                        </form>
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn btn-sm btn-outline-primary" title="Cập nhật trạng thái">
                            <i class="bi bi-arrow-repeat"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">Không có đơn hàng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $orders->links() }}
    </div>
</div>
@endsection
