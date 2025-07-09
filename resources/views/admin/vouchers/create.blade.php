@extends('layouts.admin')

@section('title', 'Thêm Voucher Mới')

@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4">Thêm Voucher Mới</h3>
    <div class="card mb-4">

        <div class="card-body">
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.vouchers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="code" class="form-label">Mã Voucher</label>
                    <input type="text" class="form-input @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                    @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="type" class="form-label">Loại Voucher</label>
                    <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                        <option value="percentage" {{ old('type') == 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                        <option value="fixed" {{ old('type') == 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
                    </select>
                    @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="value" class="form-label">Giá trị</label>
                    <input type="number" step="any" class="form-input @error('value') is-invalid @enderror" id="value" name="value" value="{{ old('value') }}" required min="0">
                    @error('value')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="min_order_amount" class="form-label">Giá trị đơn hàng tối thiểu</label>
                    <input type="number" step="any" class="form-input @error('min_order_amount') is-invalid @enderror" id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount') }}" min="0">
                    @error('min_order_amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Để trống nếu không áp dụng.</small>
                </div>
                <div class="mb-3">
                    <label for="max_discount" class="form-label">Giá trị giảm tối đa</label>
                    <input type="number" step="any" class="form-input @error('max_discount') is-invalid @enderror" id="max_discount" name="max_discount" value="{{ old('max_discount') }}" min="0">
                    @error('max_discount')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Để trống nếu không áp dụng.</small>

                </div>

                <div class="mb-3">
                    <label for="max_uses" class="form-label">Số lần sử dụng tối đa</label>
                    <input type="number" class="form-input @error('max_uses') is-invalid @enderror" id="max_uses" name="max_uses" value="{{ old('max_uses') }}" min="0">
                    @error('max_uses')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Để trống nếu không giới hạn.</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Ngày bắt đầu</label>
                        <input type="datetime-local" class="form-input @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') ?\Carbon\Carbon::parse(old('start_date'))->format('Y-m-d\TH:i') : '' }}">
                        @error('start_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">Ngày kết thúc</label>
                        <input type="datetime-local" class="form-input @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') ? \Carbon\Carbon::parse(old('end_date'))->format('Y-m-d\TH:i') : '' }}">
                        @error('end_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        <option value="1" {{ old('status', '1') == 'True' ? 'selected' : '' }}>Kích hoạt</option>
                        <option value="0" {{ old('status','0') == 'False' ? 'selected' : '' }}>Không kích hoạt</option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-input @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn-action btn-save">Lưu Voucher</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
