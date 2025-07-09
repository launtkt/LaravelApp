@extends('layouts.admin')

@section('content')
    <div class="container my-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0" style="color: #B71C1C;">Danh sách đơn hàng</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Quản lý đơn hàng</li>
                    <li class="breadcrumb-item active" aria-current="page">Danh sách đơn hàng</li>
                </ol>
            </nav>
        </div>

        <div class="row align-items-center mb-4">
            <div class="col-md-12">
                <form method="GET" action="{{ route('admin.orders.index') }}">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="d-flex">
                                <input type="text" name="search" class="form-input rounded-start flex-grow-1"
                                    placeholder="Tìm kiếm" value="{{ request('search') }}">
                                <button class="btn-action btn-tim rounded-end flex-shrink-0 ms-1"
                                    type="submit">Tìm</button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                <option value="">Trạng thái</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận
                                </option>
                                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác
                                    nhận</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang
                                    chuẩn bị hàng</option>
                                <option value="shipping" {{ request('status') == 'shipping' ? 'selected' : '' }}>Đang giao
                                    hàng</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao
                                </option>
                                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Đã trả hàng
                                </option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã huỷ
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="is_active" class="form-select" onchange="this.form.submit()">
                                <option value="">Đang hoạt động và đã khoá</option>
                                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Hoạt động
                                </option>
                                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Đã khoá</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

        </div>




        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="table-responsive mt-3 shadow rounded">
            <table class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-primary text-white">
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Trạng thái</th>
                        <th>Tổng tiền</th>
                        <th>Mã giảm giá</th>
                        <th>Tiền đã giảm</th>
                        <th>Thanh toán</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td><strong>{{ $order->code }}</strong></td>
                            <td>{{ $order->user->full_name ?? ($order->user->name ?? 'Khách vãng lai') }}
                            </td>
                            <td>
                                <span class="status-label status-{{$order->status}}">
                                    {{ ucfirst($order->status_text) }}
                                </span>
                            </td>
                            <td class="text-end text-dark">{{ number_format($order->total_price, 0, ',', '.') }}₫</td>
                            <td>{{ $order->voucher->code ?? 'Không có' }}</td>
                            <td class="text-end text-danger">{{ number_format($order->discount_amount, 0, ',', '.') }}₫
                            </td>
                            <td class="text-end text-success fw-bold">
                                {{ number_format($order->final_price, 0, ',', '.') }}₫</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                        class="btn btn-sm btn-view" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.orders.edit', $order->id) }}"
                                        class="btn btn-sm btn-edit" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    {{-- lock orunlock --}}
                                    <form action="{{ route('admin.orders.toggleStatus', $order->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Bạn có chắc muốn thay đổi trạng thái đơn hàng này?')">
                                        @csrf
                                        <button
                                            class="btn btn-sm btn-{{ $order->is_active ? 'lock' : 'unlock' }}"
                                            title="{{ $order->is_active ? 'Khoá đơn hàng' : 'Mở khoá đơn hàng' }}">
                                            <i class="bi bi-{{ $order->is_active ? 'lock' : 'unlock' }}"></i>
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-muted">Không có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
