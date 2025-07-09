@extends('layouts.admin')
@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold main-text">Danh sách bình luận</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Quản lý bình luận - đánh giá</li>
                    <li class="breadcrumb-item active" aria-current="page">Danh sách bình luận</li>
                </ol>
            </nav>
        </div>

        <form method="GET" action="{{ route('admin.comments.index') }}" class="mb-3 d-flex align-items-center"
            style="gap: 12px;">
            <div class="d-flex" style="flex: 1 1 20%;">
                <input type="text" name="search" class="form-input rounded-start"
                    style="min-width: 0;" value="{{ request('search') }}"
                    placeholder="Tìm kiếm">
                <button class="btn btn-tim rounded-end flex-shrink-0 ms-1" type="submit">Tìm</button>
            </div>

            <select name="status" class="form-select" style="flex: 1 1 20%;" onchange="this.form.submit()">
                <option value="">Tất cả trạng thái</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Từ chối</option>
            </select>
            {{-- // select lock and unlock  mặc định là unlock--}}
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
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Sản phẩm</th>
                        <th>Khách hàng</th>
                        <th class="text-start">Nội dung</th>
                        <th>Trạng thái</th>
                        <th>Ngày gửi</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr>
                            <td>{{ $comment->id }}</td>
                            <td>{{ $comment->product->name ?? '-' }}</td>
                            <td>{{ $comment->user->full_name ?? '-' }}</td>
                            <td class="text-start">{{ $comment->content }}</td>
                            <td>
                                @if ($comment->status === 'approved' && $comment->reply)
                                    <span class="badge status-confirmed">Đã phản hồi</span>
                                @elseif ($comment->status === 'approved')
                                    <a href="{{ route('admin.comments.edit', $comment->id) }}"
                                        class="btn btn-sm btn-reply">Trả lời</a>
                                @elseif ($comment->status === 'rejected')
                                    <span class="badge status-returned">Từ chối</span>
                                @else
                                    <span class="badge status-pending">Chờ duyệt</span>
                                @endif
                            </td>
                            <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.comments.edit', $comment->id) }}"
                                        class="btn btn-sm btn-edit" title="Sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('admin.comments.toggleStatus', $comment->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn thay đổi trạng thái bình luận này?')"
                                        class="d-inline">
                                        @csrf
                                        <button
                                            class="btn btn-sm btn-{{ $comment->is_active ? 'lock' : 'unlock' }}"
                                            title="{{ $comment->is_active ? 'Khoá bình luận' : 'Mở khoá bình luận' }}">
                                            <i class="bi bi-{{ $comment->is_active ? 'lock' : 'unlock' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted">Không có bình luận nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="mt-3">
            {{ $comments->links() }}
        </div>
    </div>
@endsection
