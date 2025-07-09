@extends('layouts.admin')

@section('content')
<div class="container my-4">
    <h3>Thêm ảnh sản phẩm</h3>

    <div class="card">
        <div class="card-body">
            {{-- Chọn sản phẩm --}}
            <form method="GET" action="{{ route('admin.images.create') }}">
                <div class="mb-3">
                    <label class="form-label">Chọn sản phẩm</label>
                    <select name="product_id" class="form-select" onchange="this.form.submit()" required>
                        <option value="">-- Chọn sản phẩm --</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}"
                                {{ $selectedProductId == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ $product->images->count() }}/4 ảnh)
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>

            {{-- Nếu đã chọn sản phẩm --}}
            @if ($selectedProduct)
                <div class="card mt-4">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.images.store') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $selectedProduct->id }}">

                            @if ($needMainImage)
                                <div class="mb-3">
                                    <label class="form-label">Ảnh chính</label>
                                    <input type="file" name="images[]" class="form-input"  required>
                                    <input type="hidden" name="is_main[]" value="1">
                                </div>
                            @endif

                            @for ($i = 0; $i < $needAdditional; $i++)
                                <div class="mb-3">
                                    <label class="form-label">Ảnh phụ {{ $i + 1 }}</label>
                                    <input type="file" name="images[]" class="form-input" required>
                                    <input type="hidden" name="is_main[]" value="0">
                                </div>
                            @endfor

                            <button type="submit" class="btn btn-save">Lưu ảnh</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
