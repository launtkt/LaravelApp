@extends('layouts.admin')
@section('content')
<div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Danh sách sản phẩm</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Quản lý sản phẩm</li>
                    <li class="breadcrumb-item active" aria-current="page">Danh sách sản phẩm</li>
                </ol>
            </nav>
        </div>
        <form method="GET" action="{{ route('admin.products.index')}}">
            <div class="row justify-content-between">
            <div class="col-12 col-md-4 j">
                <div class="input-group mb-3">
                    <input type="text" name="search" class="form-control" value="{{request('search') }}" placeholder="Search">
                    <button class="btn btn-secondery border" type="submit">Tìm kiếm</button>
                </div>
            </div>
            <div class="col-12 col-md-3">
<a href="{{ route('admin.products.create') }}" class="btn btn-primary w-100">Thêm sản phẩm mới</a>
            </div>
        </div>
        </form>
        <div class="table">
            <table class="table table-bordered table-striped text-center align-middle">
                <tr style="background-color: grey;">
                    <th>Ảnh 1</th>
                    <th>Sản phẩm</th>
                    <th>Mã sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>

                    <th>Hành động</th>
                </tr>
                @forelse ($products as $product)
                <tr>
                    <td>
                        @if(!empty($product->image1) && file_exists(public_path('storage/'.$product->image1)))
                            <img src="{{ asset('storage/'.$product->image1) }}" alt="Ảnh sản phẩm" width="120">
                        @else
                            <span class="text-muted">Chưa có ảnh</span>
                        @endif
                    </td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->product_code }}</td>
                    <td>{{ $product->category->name ?? 'Chưa phân loại' }}</td>
                    <td>{{ number_format($product->price, 0, ',', '.') }} VNĐ</td>
                    <td>{{ $product->quantity }}</td>
                    <td>{{ $product->status  }}</td>

                    <td>
                        <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-outline-dark me-1" title="Xem"><i class="bi bi-eye"></i></a>
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-outline-dark me-1" title="Sửa"><i class="bi bi-pencil"></i></a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-inline">
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
                    <td colspan="10">Không có sản phẩm nào.</td>
                </tr>
                @endforelse
            </table>
        </div>
        <div class="mt-3">
            {{ $products->links()}}
        </div>
    </div>
@endsection
