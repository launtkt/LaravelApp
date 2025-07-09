@extends('layouts.admin')

@section('content')
    <div class="container-fluid my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0" style="color: #B71C1C;">Danh sách voucher</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Quản lý voucher</li>
                    <li class="breadcrumb-item active" aria-current="page">Danh sách voucher</li>
                </ol>
            </nav>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- form GET index tìm kiếm voucher, lọc theo loại, lọc theo trạng thái và lọc theo is_active --}}
        <form method="GET" action="{{ route('admin.vouchers.index') }}" class="row mb-3 g-2">
            <div class="col-md-3">
                <div class="d-flex">
                    <input type="text" name="search" class="form-input rounded-start flex-grow-1"
                        style="min-width: 0;"  {{-- Đặt min-width: 0 để input có thể co giãn --}}
                     placeholder="Tìm theo mã voucher"
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-tim rounded-end flex-shrink-0 ms-1">Tìm</button>
                </div>
            </div>

            <div class="col-md-2">
                <select name="type" class="form-select" onchange="this.form.submit()">
                    <option value="">Tất cả loại</option>
                    <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>Theo phần trăm (%)
                    </option>
                    <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>Giảm cố định (VNĐ)</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Kích hoạt</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Đã hết hạn</option>
                </select>
            </div>

            <div class="col-md-2">
                <select name="is_active" class="form-select" onchange="this.form.submit()">
                    <option value="">Trạng thái hoạt động</option>
                    <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Đã khoá</option>
                </select>
            </div>

            <div class="col-md-3 text-md-end">
                <a href="{{ route('admin.vouchers.create') }}" class="btn btn-add w-100">Thêm voucher</a>
            </div>
        </form>

        <div class="table-responsive mt-4 shadow rounded">
            <table class="table table-bordered align-middle text-center mb-0">
                <thead class="table-primary text-white">
                    <tr>
                        <th>ID</th>
                        <th>Mã</th>
                        <th>Loại</th>
                        <th>Giá trị</th>
                        <th>Đơn tối thiểu</th>
                        <th>Giảm tối đa</th>
                        <th>Lượt dùng</th>
                        <th>Hiệu lực</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vouchers as $voucher)
                        <tr>
                            <td>{{ $voucher->id }}</td>
                            <td><strong>{{ $voucher->code }}</strong></td>
                            <td>
                                {{ $voucher->type === 'percentage' ? 'Theo phần trăm (%)' : 'Giảm cố định (VNĐ)' }}
                            </td>
                            <td>
                                @if ($voucher->type === 'percentage')
                                    {{ $voucher->value }}%
                                @else
                                    {{ number_format($voucher->value, 0, ',', '.') }} VNĐ
                                @endif
                            </td>
                            <td>
                                {{ $voucher->min_order_amount ? number_format($voucher->min_order_amount, 0, ',', '.') . ' VNĐ' : 'Không' }}
                            </td>
                            <td>
                                @if ($voucher->type === 'percentage')
                                    {{ $voucher->max_discount ? number_format($voucher->max_discount, 0, ',', '.') . ' VNĐ' : 'Không giới hạn' }}
                                @else
                                    {{ $voucher->value ? number_format($voucher->value, 0, ',', '.') . ' VNĐ' : 'Không giới hạn' }}
                                @endif
                            </td>
                            <td>{{ $voucher->used }} / {{ $voucher->max_uses ?? '∞' }}</td>
                            <td>
                                {{ optional($voucher->start_date)->format('d/m/Y') ?? '-' }} -
                                {{ optional($voucher->end_date)->format('d/m/Y') ?? '-' }}
                            </td>
                            <td>
                                <span class="badge status-{{ $voucher->status ? 'confirmed' : 'cancelled' }}">
                                    {{ $voucher->status ? 'Kích hoạt' : 'Đã hết hạn' }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                                        class="btn-action btn-sm btn-edit" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.vouchers.toggleStatus', $voucher->id) }}" method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Xác nhận thay đổi trạng thái voucher này?')">
                                        @csrf
                                        <button
                                            class="btn-action btn-sm btn-outline-{{ $voucher->is_active ? 'lock' : 'unlock' }}"
                                            title="{{ $voucher->is_active ? 'Khoá' : 'Mở khoá' }}">
                                            <i class="bi bi-{{ $voucher->is_active ? 'lock' : 'unlock' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-muted">Không có voucher nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="d-flex justify-content-end">
            {{ $vouchers->links() }}
        </div>
    </div>
@endsection
