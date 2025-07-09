@extends('layouts.admin')

@section('content')
    <div class="container my-4">
        <div class="row mb-3">
            <div class="col">
                <h3 class="fw-bold">Sửa mã giảm giá / {{ $voucher->code }}</h3>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="card mb-4">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.vouchers.update', $voucher->id) }}">
            @csrf
            @method('PUT')
            <div class="row g-4">
                <!-- Cột trái -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Mã voucher</label>
                        <input type="text" name="code" class="form-input" value="{{ old('code', $voucher->code) }}"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Loại giảm giá</label>
                        <select name="type" class="form-select" required>
                            <option value="percentage" {{ old('type', $voucher->type) == 'percentage' ? 'selected' : '' }}>
                                Phần trăm</option>
                            <option value="fixed" {{ old('type', $voucher->type) == 'fixed' ? 'selected' : '' }}>Giá cố
                                định (VNĐ)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá trị giảm</label>
                        <input type="number" name="value" class="form-input" step="1" min="0"
                            value="{{ old('value', $voucher->value) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Giảm tối đa (VNĐ)</label>
                        <input type="number" name="max_discount" class="form-input" step="1" min="0"
                            value="{{ old('max_discount', $voucher->max_discount) }}">
                        <small class="form-text text-muted">Chỉ áp dụng với loại phần trăm (%). Có thể bỏ trống nếu không
                            giới hạn.</small>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Số lượt sử dụng tối đa</label>
                        <input type="number" name="max_uses" class="form-input" step="1" min="1"
                            value="{{ old('max_uses', $voucher->max_uses) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label><br>
                        <div class="form-check form-switch">
                            <!-- Hidden input: nếu checkbox không check thì gửi giá trị 0 -->
                            <input type="hidden" name="status" value="0">

                            <!-- Checkbox: nếu check thì ghi đè thành 1 -->
                            <input class="form-check-input" type="checkbox" name="status" value="1" id="statusSwitch"
                                {{ old('status', $voucher->status) ? 'checked' : '' }}>
                            <label class="form-check-label" for="statusSwitch">Đang kích hoạt</label>
                        </div>
                    </div>

                </div>

                <!-- Cột phải -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Đơn hàng tối thiểu (VNĐ)</label>
                        <input type="number" name="min_order_amount" class="form-input" step="1" min="0"
                            value="{{ old('min_order_amount', $voucher->min_order_amount) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input type="datetime-local" name="start_date" class="form-input"
                            value="{{ old('start_date', $voucher->start_date ? $voucher->start_date->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ngày kết thúc</label>
                        <input type="datetime-local" name="end_date" class="form-input"
                            value="{{ old('end_date', $voucher->end_date ? $voucher->end_date->format('Y-m-d\TH:i') : '') }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả (tuỳ chọn)</label>
                        <textarea name="description" class="form-input" rows="3">{{ old('description', $voucher->description) }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-save px-5">Cập nhật</button>
        </form>
            </div>
        </div>

    </div>
@endsection
