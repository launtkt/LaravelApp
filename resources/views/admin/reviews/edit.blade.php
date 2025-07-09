@extends('layouts.admin')
@section('content')
<div class="container my-4">
    <h3>Sửa đánh giá</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.reviews.update', $review->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Sản phẩm</label>
            <input type="text" class="form-control" value="{{ $review->product->name ?? 'N/A' }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Khách hàng</label>
            <input type="text" class="form-control" value="{{ $review->user->full_name ?? 'N/A' }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Số sao</label>
            <input type="text" class="form-control" value="{{ $review->stars ?? 'N/A' }}" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Đánh giá</label>
            <input type="text" class="form-control" value="{{ $review->review ?? 'N/A' }}" disabled>
        </div>

        <div class="mb-3">
    <label class="form-">Trạng thái</label>
    <select name="status" id="status-select" class="form-select">
        <option value="pending" {{ old('status', $review->status) == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
        <option value="approved" {{ old('status', $review->status) == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
        <option value="rejected" {{ old('status', $review->status) == 'rejected' ? 'selected' : '' }}>Từ chối</option>
    </select>
</div>

        <div class="mb-3" id="reply-group" style="{{ old('status', $review->status) == 'approved' ? '' : 'display: none' }}">
            <label>Phản hồi</label>
            <textarea name="reply" class="form-input" rows="3">{{ old('reply', $review->reply) }}</textarea>
            @error('reply') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-save">Cập nhật</button>
    </form>
        </div>
    </div>

</div>
<script>
    const statusSelect = document.getElementById('status-select');
    const replyGroup = document.getElementById('reply-group');

    statusSelect.addEventListener('change', function () {
        if (this.value === 'approved') {
            replyGroup.style.display = '';
        } else {
            replyGroup.style.display = 'none';
        }
    });
</script>
@endsection
