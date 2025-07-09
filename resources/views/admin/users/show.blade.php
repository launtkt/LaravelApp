@extends('layouts.admin')
@section('content')
    <div class="container my-4">
        <h3>Thông tin chi tiết người dùng</h3>

        <div class="row mb-4">
            <div class="col-md-4 text-center align-self-center">
                @if ($user->avatar)
                    <p><strong>Ảnh đại diện</strong></p>
                    <img src="{{ asset('storage/' . $user->avatar) }}" class="img-thumbnail" style="max-width: 200px;">
                @else
                    <p class="text-muted">Không có ảnh đại diện</p>
                @endif
            </div>
            <div class="col-md-8">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $user->id }}</td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td>{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <th>Họ tên</th>
                        <td>{{ $user->full_name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Phone</th>
                        <td>{{ $user->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Địa chỉ</th>
                        <td>{{ $user->address ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Giới tính</th>
                        <td>{{ $user->gender === 'male' ? 'Nam' : ($user->gender === 'female' ? 'Nữ' : 'Khác') }}</td>
                    </tr>
                    <tr>
                        <th>Ngày sinh</th>
                        <td>{{ $user->birthday ? $user->birthday->format('d/m/Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <th>Vai trò</th>
                        <td>
                            <span
                                class="badge bg-{{ $user->role == 'admin' ? 'success' : 'secondary' }}">{{ ucfirst($user->role) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Trạng thái</th>
                        <td>
                            <span class="badge bg-{{ $user->status ? 'primary' : 'danger' }}">
                                {{ $user->status ? 'Hoạt động' : 'Đã khoá' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <h4>Đơn hàng đã mua</h4>
        <table class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-primary text-white">
                <tr>
                    <th>Mã đơn</th>
                    <th>Ngày</th>
                    <th>Sản phẩm</th>
                    <th>Tổng tiền</th>
                    <th>Voucher</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td>#{{ $order->code }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>
                            <ul class="list-unstyled mb-0">
                                @foreach ($order->items as $item)
                                    <li>{{ $item->product->name }} x {{ $item->quantity }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ number_format($order->final_price, 0, ',', '.') }}₫</td>
                        <td>{{ $order->voucher->code ?? '-' }}</td>
                        <td><span class="badge bg-info">{{ ucfirst($order->status) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-muted">Không có đơn hàng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <hr>

        <h4>Voucher đã lưu</h4>
        <table class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-primary text-white">
                <tr>
                    <th>Mã</th>
                    <th>Giảm</th>
                    <th>Hạn sử dụng</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vouchers as $voucher)
                    <tr>
                        <td>{{ $voucher->code }}</td>
                        <td>
                            @if ($voucher->type === 'percentage')
                                {{ $voucher->value }}%
                            @else
                                {{ number_format($voucher->value, 0, ',', '.') }} VNĐ
                            @endif
                        </td>
                        <td>{{ $voucher->start_date }} → {{ $voucher->end_date }}</td>
                        <td>
                            {{ $voucher->pivot->uses_remaining ? 'Còn khả dụng' : 'Hết lượt dùng' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-muted">Chưa có voucher nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
