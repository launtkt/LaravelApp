{{-- filepath: resources/views/admin/orders/show.blade.php --}}
@extends('layouts.admin')
@section('content')
<div class="container my-4">
    <h3>Chi tiết đơn hàng #{{ $order->code ?? $order->id }}</h3>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary mb-3">Quay lại danh sách</a>

    {{-- Thông tin khách hàng --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">Thông tin khách hàng</div>
        <div class="card-body">
            <div><b>Tên:</b> {{ $order->customer->name ?? $order->customer_name ?? '---' }}</div>
            <div><b>Địa chỉ:</b> {{ $order->customer->address ?? $order->customer_address ?? '---' }}</div>
            <div><b>Điện thoại:</b> {{ $order->customer->phone ?? $order->customer_phone ?? '---' }}</div>
            <div><b>Email:</b> {{ $order->customer->email ?? $order->customer_email ?? '---' }}</div>
        </div>
    </div>

    {{-- Danh sách sản phẩm --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">Danh sách sản phẩm trong đơn hàng</div>
        <div class="card-body p-0">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Số lượng</th>
                        <th>Đơn giá</th>
                        <th>Tổng tiền</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td>
                            @if(!empty($item->product->image1) && file_exists(public_path('storage/'.$item->product->image1)))
                                <img src="{{ asset('storage/'.$item->product->image1) }}" alt="Ảnh" width="60">
                            @else
                                <span class="text-muted">Không có ảnh</span>
                            @endif
                        </td>
                        <td>{{ $item->product->name ?? '---' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->price, 0, ',', '.') }}₫</td>
                        <td>{{ number_format($item->price * $item->quantity, 0, ',', '.') }}₫</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Phí ship, mã giảm giá --}}
    <div class="row mb-2">
        <div class="col-md-6">
            <b>Phí ship:</b> {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }}₫
        </div>
        <div class="col-md-6">
            <b>Mã giảm giá:</b> {{ $order->discount_code ?? '---' }}
            @if(isset($order->discount_amount) && $order->discount_amount > 0)
                (Giảm {{ number_format($order->discount_amount, 0, ',', '.') }}₫)
            @endif
        </div>
    </div>

    {{-- Tổng thanh toán cuối cùng --}}
    <div class="mb-3">
        <b>Tổng thanh toán cuối cùng:</b>
        <span class="fs-5 text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
    </div>

    {{-- Lịch sử thay đổi trạng thái --}}
    <div class="card mb-3">
        <div class="card-header fw-bold">Lịch sử thay đổi trạng thái đơn hàng</div>
        <div class="card-body p-0">
            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th>Thời gian</th>
                        <th>Trạng thái</th>
                        <th>Người cập nhật</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->statusHistories as $history)
                    <tr>
                        <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $history->status_label }}</td>
                        <td>{{ $history->user->name ?? '---' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3">Chưa có lịch sử thay đổi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Ghi chú --}}
    <div class="mb-3">
        <b>Ghi chú:</b> {{ $order->note ?? '---' }}
    </div>
</div>
@endsection
