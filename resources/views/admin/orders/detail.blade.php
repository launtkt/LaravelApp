@extends('layouts.admin')

@section('content')
@php
    $statusLabels = [
        'pending' => 'Chờ xác nhận',
        'confirmed' => 'Đã xác nhận',
        'processing' => 'Đang chuẩn bị hàng',
        'shipping' => 'Đang giao hàng',
        'delivered' => 'Đã giao',
        'returned' => 'Đã trả hàng',
        'cancelled' => 'Đã huỷ',
    ];
@endphp
    <div class="container mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Chi tiết đơn hàng {{ $order->code }}</h3>
        </div>

        {{-- Thông báo --}}
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="row mt-4 d-flex justify-content-between">
            {{-- Thông tin khách hàng --}}
            <div class="col-md-8 ">
                <div class="card shadow-sm border-pink">
                    <div class="card-header bg-soft-pink fw-bold text-primary-pink">Thông tin khách hàng</div>
                    <div class="card-body ">
                        <p><strong>Tên:</strong> {{ $order->customer->full_name ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $order->customer->email ?? 'N/A' }}</p>
                        <p><strong>Địa chỉ:</strong> {{ $order->customer->address ?? 'N/A' }}</p>
                        <p><strong>SĐT:</strong> {{ $order->customer->phone ?? 'N/A' }}</p>
                        <hr>
                        <div class="fw-bold text-primary-pink">Chi tiết sản phẩm</div>
                        <div class="card-body table-responsive ">
                            <table class="table table-bordered align-middle ">
                                <thead class="table-primary">
                                    <tr>
                                        <th>Ảnh</th>
                                        <th>Sản phẩm</th>
                                        <th>Đơn giá</th>
                                        <th>SL</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td>
                                                @php
                                                    $mainImage =
                                                        $item->product->images->where('is_main', true)->first() ??
                                                        $item->product->images->first();
                                                @endphp
                                                @if ($mainImage)
                                                    <img src="{{ asset($mainImage->path) }}" width="50"
                                                        alt="{{ $item->product->name }}">
                                                @else
                                                    <span class="text-muted">Chưa có ảnh</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->product->name }}</td>
                                            <td>{{ number_format($item->product->price, 0, ',', '.') }} VNĐ</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ number_format($item->product->price * $item->quantity, 0, ',', '.') }} VNĐ</td>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="d-flex justify-content-end mt-3">
                                <div class="border rounded p-3 border-pink" style="width: 400px;">
                                    <div class="d-flex justify-content-between">
                                        <p><strong>Tổng tiền sản phẩm:</strong> </p>
                                        <span>{{ number_format($order->total_price, 0, ',', '.') }}
                                            VNĐ</span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <p><strong>Mã giảm giá:</strong></p>
                                        <span> {{ $order->voucher->code ?? 'Không có' }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <p><strong>Tiền giảm:</strong></p>
                                        <span> {{ number_format($order->discount_amount, 0, ',', '.') }} VNĐ</span>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <h4 class="text-primary-pink"><strong>Tổng thanh toán:</strong>

                                        </h4>
                                        <span>{{ number_format($order->final_price, 0, ',', '.') }} VNĐ</span>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-4">
                <div class="card shadow-sm card-pink">
            <div class="card-header bg-soft-pink fw-bold text-primary-pink">Thông tin đơn hàng</div>
            <div class="card-body">
                <p><strong>Mã đơn hàng:</strong> {{ $order->id }}</p>
                <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at }}</p>
                <p><strong>Trạng thái:</strong> {{ $statusLabels[$order->status] }}</p>
            </div>
        </div>
            </div>
        </div>

        {{-- Chi tiết sản phẩm --}}

    </div>
@endsection
