@extends('layouts.admin')

@section('content')
    <div class="container my-1">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Chỉnh sửa đơn hàng #{{ $order->code }}</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Quản lý đơn hàng</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa đơn hàng</li>
                </ol>
            </nav>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card">
                <div class="card-header">
                    Thông tin đơn hàng
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>ID đơn hàng:</strong></label>
                            <p>{{ $order->id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Khách hàng:</strong></label>
                            <p>{{ $order->customer->full_name ?? ($order->customer->account->name ?? 'Khách vãng lai') }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Mã đơn hàng:</strong></label>
                            <p>{{ $order->code ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label"><strong>Ngày tạo:</strong></label>
                            <p>{{ $order->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label"><strong>Tổng tiền sản phẩm:</strong></label>
                            <p>{{ number_format($order->total_price, 0, ',', '.') }} VNĐ</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label"><strong>Mã giảm giá:</strong></label>
                            <p>{{ $order->voucher->code ?? 'Không có' }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label"><strong>Giảm giá:</strong></label>
                            <p>{{ number_format($order->discount_amount, 0, ',', '.') }} VNĐ</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label"><strong>Thành tiền:</strong></label>
                            <p>{{ number_format($order->final_price, 0, ',', '.') }} VNĐ</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="status" class="form-label"><strong>Trạng thái đơn hàng</strong></label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                            required>
                            <option value="pending" {{ old('status', $order->status) == 'pending' ? 'selected' : '' }}>Chờ
                                xử lý</option>
                            <option value="confirmed" {{ old('status', $order->status) == 'confirmed' ? 'selected' : '' }}>
                               Đã xác nhận</option>
                            <option value="processing"
                                {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipping" {{ old('status', $order->status) == 'shipping' ? 'selected' : '' }}>
                                Đang giao hàng</option>
                            <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>
                                Đã giao hàng</option>
                            <option value="returned" {{ old('status', $order->status) == 'returned' ? 'selected' : '' }}>
                                Trả hàng/ Hoàn tiền</option>
                            <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>
                                Đã hủy</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-action btn-save">Cập nhật đơn hàng</button>
                </div>
            </div>
        </form>
    </div>
@endsection
