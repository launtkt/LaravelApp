@extends('layouts.admin')
@section('content')
    <div class="container my-4">
        <h3 class="fw-bold main-text">Sửa bình luận</h3>
        <div class="card-pink p-3">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.comments.update', $comment->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label>Sản phẩm</label>
                        <input type="text" class="form-control" value="{{ $comment->product->name ?? 'N/A' }}" disabled>
                    </div>

                    <div class="mb-3">
                        <label>Khách hàng</label>
                        <input type="text" class="form-control" value="{{ $comment->customer->full_name ?? 'N/A' }}"
                            disabled>
                    </div>

                    <div class="mb-3">
                        <label>Nội dung</label>
                        <textarea name="content" class="form-control" rows="3" disabled>{{ old('content', $comment->content) }}</textarea>
                        @error('content')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label>Trạng thái</label>
                        <select name="status" class="form-select" id="status-select">
                            <option value="pending" {{ old('status', $comment->status) == 'pending' ? 'selected' : '' }}>Chờ
                                duyệt</option>
                            <option value="approved" {{ old('status', $comment->status) == 'approved' ? 'selected' : '' }}>
                                Đã duyệt</option>
                            <option value="rejected" {{ old('status', $comment->status) == 'rejected' ? 'selected' : '' }}>
                                Từ chối</option>
                        </select>
                    </div>

                    <!-- PHẢN HỒI -->
                    <div class="mb-3" id="reply-group"
                        style="{{ old('status', $comment->status) == 'approved' ? '' : 'display: none' }}">
                        <label>Phản hồi</label>
                        <textarea name="reply" class="form-control" rows="3">{{ old('reply', $comment->reply) }}</textarea>
                        @error('reply')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-save">Cập nhật</button>
                </form>
            </div>

        </div>

    </div>

    <script>
        const statusSelect = document.getElementById('status-select');
        const replyGroup = document.getElementById('reply-group');

        statusSelect.addEventListener('change', function() {
            if (this.value === 'approved') {
                replyGroup.style.display = '';
            } else {
                replyGroup.style.display = 'none';
            }
        });
    </script>
@endsection
