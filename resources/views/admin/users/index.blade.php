@extends('layouts.admin')
@section('content')
    <div class="container my-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="fw-bold main-text">Danh sách người dùng</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Quản lý người dùng</li>
                    <li class="breadcrumb-item active" aria-current="page">Danh sách</li>
                </ol>
            </nav>
        </div>

        <form method="GET" action="{{ route('admin.users.index') }}">
            <div class="row d-flex align-items-center mb-4">
                <div class="col-12 col-md-4">
                    <div class="d-flex">
                        <input type="text" name="search" class="form-input rounded-start flex-grow-1"
                            style="min-width: 0;" value="{{ request('search') }}"
                            placeholder="Tìm kiếm theo email, tên, username">
                        <button class="btn btn-tim rounded-end flex-shrink-0 ms-1" type="submit">Tìm</button>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select name="role" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả quyền</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="customer" {{ request('role') == 'customer' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select name="status" class="form-select" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Bị khoá</option>
                    </select>
                </div>
                <div class="col-12 col-md-2 ms-auto">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-add w-100">Tạo admin mới</a>
                </div>
            </div>
        </form>

        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif

        <div class="table-responsive mt-3">
            <table class="table table-hover table-bordered align-middle text-center mb-0">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Username</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Quyền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                @if ($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar"
                                        class="rounded-circle border" style="width: 50px; height: 50px; object-fit: cover;">
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-role-{{ $user->role === 'admin' ? 'admin' : 'customer' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if ($user->status)
                                    <span class="badge status-active">Hoạt động</span>
                                @else
                                    <span class="badge status-locked">Bị khoá</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.users.show', $user->id) }}"
                                        class="btn btn-sm btn-view" title="Chi tiết">
                                        <i class="bi bi-eye-fill"></i>
                                    </a>
                                    <form action="{{ route('admin.users.toggleStatus', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button class="btn btn-sm btn-{{ $user->status ? 'lock' : 'unlock' }}"
                                            title="{{ $user->status ? 'Khoá' : 'Mở khoá' }}">
                                            <i class="bi bi-{{ $user->status ? 'lock' : 'unlock' }}"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-muted">Không có người dùng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3">
                {{ $users->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
