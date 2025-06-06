{{-- filepath: resources/views/admin/customers/index.blade.php --}}
@extends('layouts.admin')
@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Danh sách khách hàng</h3>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Quản lý khách hàng</li>
                <li class="breadcrumb-item active" aria-current="page">Danh sách khách hàng</li>
            </ol>
        </nav>
    </div>
    <form method="GET" action="{{ route('admin.customers.index') }}">
        <div class="row justify-content-between">
            <div class="col-12 col-md-4">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Tìm kiếm tên, email, SĐT...">
                    <button class="btn btn-secondary border" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered table-striped text-center align-middle">
            <thead class="table-secondary">
                <tr>
                    <th>ID / Mã khách hàng</th>
                    <th>Tên khách hàng</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Địa chỉ</th>
                    <th>Số đơn hàng</th>
                    <th>Tổng chi tiêu</th>
                    <th>Ngày đăng ký</th>
                    <th>Trạng thái</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                <tr>
                    <td>{{ $customer->code ?? $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone }}</td>
                    <td>{{ $customer->address }}</td>
                    <td>{{ $customer->orders_count ?? 0 }}</td>
                    <td>{{ number_format($customer->orders_sum_total_amount ?? 0, 0, ',', '.') }}₫</td>
                    <td>{{ $customer->created_at->format('d/m/Y') }}</td>
                    <td>
                        @if($customer->status == 'active')
                            <span class="badge bg-success">Hoạt động</span>
                        @elseif($customer->status == 'locked')
                            <span class="badge bg-danger">Khoá</span>
                        @else
                            <span class="badge bg-secondary">Chưa xác thực</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.customers.show', $customer->id) }}" class="btn btn-sm btn-outline-dark me-1" title="Xem"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-outline-dark me-1" title="Sửa"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-dark" onclick="return confirm('Bạn có chắc muốn xoá?')" title="Xoá">
                                <i class="bi bi-trash2-fill"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">Không có khách hàng nào.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        {{ $customers->links() }}
    </div>
</div>
@endsection
