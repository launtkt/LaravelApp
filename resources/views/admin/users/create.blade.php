@extends('layouts.admin')
@section('content')
<div class="container my-4">
    <h3 class="fw-bold main-text">Thêm tài khoản Admin</h3>
    <div class="card-pink p-3">

        {{-- Thông báo lỗi --}}
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Ảnh đại diện</label>
            <input type="file" name="avatar" class="form-input">
            @error('avatar') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">User Name</label>
            <input type="text" name="username" class="form-input" value="{{ old('username') }}" required>
            @error('username') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-input" value="{{ old('email') }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Mật khẩu</label>
            <input type="password" name="password" class="form-input" required>
            @error('password') <small class="text-danger">{{ $message }}</small> @enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Nhập lại mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-input" required>
        </div>

        <button type="submit" class="btn btn-save">Tạo Admin</button>
    </form>
        </div>
    </div>

</div>
@endsection
