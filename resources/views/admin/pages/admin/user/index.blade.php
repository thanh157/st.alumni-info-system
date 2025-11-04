@extends('admin.layouts.master')

@section('title', 'Người dùng')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h4 class="fw-bold mb-1">Quản lý người dùng</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">Người dùng</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Danh sách người dùng</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Tìm kiếm và lọc -->
        <form method="GET" class="mb-3">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="keyword" class="form-control" placeholder="Tìm theo họ tên"
                        value="{{ request('keyword') }}">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search me-1"></i>Tìm kiếm
                    </button>
                </div>
            </div>
        </form>

        <!-- Hiển thị thông báo thành công -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Hiển thị thông báo lỗi -->
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Bảng dữ liệu -->
        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Họ tên</th>
                            <th>Loại tài khoản</th>
                            <th>Vai trò</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 + ($users->currentPage() - 1) * $users->perPage() }}</td>
                                <td>
                                    @can('view', $user)
                                        <a class="fw-semibold" href="{{ route('admin.users.show', $user->id) }}">
                                            <img src="{{ Avatar::create($user->full_name)->toBase64() }}" class="w-32px h-32px"
                                                alt="">
                                            {{ $user->full_name }}
                                        </a>
                                    @else
                                        <span class="text-muted">
                                            <img src="{{ Avatar::create($user->full_name)->toBase64() }}" class="w-32px h-32px"
                                                alt="">
                                            {{ $user->full_name }}
                                        </span>
                                    @endcan

                                </td>
                                <td>{{ $user->type->name }}</td>
                                <td>
                                    @if ($user->userRoles->isNotEmpty())
                                        {{ $user->userRoles->pluck('name')->join(', ') }}
                                    @else
                                        Chưa có vai trò
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Phân trang -->
                @if (method_exists($users, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
