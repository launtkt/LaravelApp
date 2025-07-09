@extends('layouts.admin')
@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0" style="color: #B71C1C;">Danh sách đánh giá</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Quản lý bình luận - đánh giá</li>
                    <li class="breadcrumb-item active" aria-current="page">Danh sách đánh giá </li>
                </ol>
            </nav>
        </div>

        <form method="GET" action="{{ route('admin.reviews.index') }}" class="mb-3 d-flex align-items-center"
            style="gap: 12px;">
            <div class="d-flex" style="flex: 1 1 20%;">
                <input type="text" name="search" class="form-input rounded-start flex-grow-1"
                    style="min-width: 0;" value="{{ request('search') }}"
                    placeholder="Tìm kiếm">
                <button class="btn btn-tim rounded-end flex-shrink-0 ms-1" type="submit">Tìm kiếm</button>
            </div>

            <select name="status" class="form-select" style="flex: 1 1 20%;" onchange="this.form.submit()">
                <option value="">Tất cả trạng thái</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
            </select>
            <select name="is_active" class="form-select" style="flex: 1 1 20%;" onchange="this.form.submit()">
                <option value="">Đang hoạt động và đã khoá</option>
                <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Hoạt động</option>
                <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Đã khoá</option>
            </select>
        </form>


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive mt-3 shadow rounded">
            <table class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-primary text-white">
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Khách hàng</th>
                        <th>Số sao</th>
                        <th>Đánh giá</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>{{ $review->product->name ?? 'N/A' }}</td>
                            <td>{{ $review->user->full_name ?? 'N/A' }}</td>
                            <td>
                                @for ($i = 0; $i < $review->stars; $i++)
                                    <i class="bi bi-star-fill text-warning"></i>
                                @endfor
                                @for ($i = $review->stars; $i < 5; $i++)
                                    <i class="bi bi-star text-muted"></i>
                                @endfor
                            </td>
                            <td class="text-start">{{ $review->review }}</td> {{-- căn trái cho dễ đọc --}}
                            <td>
                                @if ($review->status === 'approved' && $review->reply)
                                    <span class="badge status-confirmed">Đã phản hồi</span>
                                @elseif ($review->status === 'approved')
                                    <a href="{{ route('admin.reviews.edit', $review->id) }}"
                                        class="btn btn-sm btn-reply">Trả lời</a>
                                @elseif ($review->status === 'rejected')
                                    <span class="badge status-returned">Từ chối</span>
                                @else
                                    <span class="badge status-pending">Chờ duyệt</span>
                                @endif
                            </td>
                            <td>{{ $review->created_at?->format('d/m/Y H:i') ?? 'Chưa có' }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.reviews.edit', $review->id) }}"
                                        class="btn btn-sm btn-edit" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.reviews.toggleStatus', $review->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn thay đổi trạng thái đánh giá này?')"
                                        class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-{{ $review->is_active ? 'lock' : 'unlock' }}"
                                            title="{{ $review->is_active ? 'Khoá đánh giá' : 'Mở khoá đánh giá' }}">
                                            <i class="bi bi-{{ $review->is_active ? 'lock' : 'unlock' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted">Không có đánh giá nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="mt-3">
            {{ $reviews->links() }}
        </div>
    </div>
@endsection
