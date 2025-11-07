@extends('admin.layouts.master')

@section('title', 'Người dùng')

@section('content')
    <div class="d-lg-flex align-items-lg-start p-4" style="padding: 20px;">

        <!-- Right content -->
        <div class="tab-content flex-fill">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold mb-1">Quản lý người dùng</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.users.index') }}">Người dùng</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Danh sách người dùng</li>
                            <li class="breadcrumb-item active" aria-current="page">Vai trò</li>

                        </ol>
                    </nav>
                </div>
            </div>

            <!-- Thông tin người dùng -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Thông tin người dùng</h6>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <p><b>Tên đăng nhập:</b> {{ $user->username ?? '-' }}</p>
                            <p><b>Email:</b> {{ $user->email ?? '-' }}</p>
                            <p><b>Số điện thoại:</b> {{ $user->phone ?? '-' }}</p>
                        </div>

                        <div class="col-md-6 col-12">
                            <p><b>Họ và tên:</b> {{ $user->full_name ?? '-' }}</p>
                            <p><b>Loại người dùng:</b> {{ $user->type->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Thông tin người dùng -->


            <!-- Danh sách vai trò -->
            <div class="card">
                <div class="py-3 card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Danh sách vai trò</h6>

                    <!-- Form tìm kiếm -->
                    <form method="GET" action="{{ url()->current() }}" class="d-flex">
                        <input type="text" name="keyword" class="form-control me-2" placeholder="Tìm kiếm vai trò..."
                            value="{{ request('keyword') }}">
                        <button type="submit" class="btn btn-primary">Tìm</button>
                    </form>
                </div>

                <form action="{{ route('admin.users.updateRoles', $user->id) }}" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table fs-table">
                            <thead>
                                <tr class="table-light">
                                    <th width="5%"></th>
                                    <th width="40%">Tên vai trò</th>
                                    <th width="40%">Mô tả</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $item)
                                    <tr>
                                        <td class="text-center" width="5%">
                                            <input type="checkbox" name="role_ids[]" value="{{ $item->id }}"
                                                @if ($user->userRoles->contains('id', $item->id)) checked @endif>
                                        </td>
                                        <td width="40%">
                                            <span class="fw-semibold">{{ $item->name }}</span>
                                        </td>
                                        <td width="40%">{{ $item->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Nút cập nhật -->
                    <div class="card-footer text-end">
                        @can('assignRole', $user)
                        <button type="submit" class="btn btn-success">Cập nhật vai trò</button>
                        @endcan
                    </div>
                </form>

                <!-- Phân trang -->
                @if (method_exists($roles, 'links'))
                    <div class="d-flex justify-content-center mt-3">
                        {{ $roles->links('pagination::bootstrap-5') }}
                    </div>
                @endif
            </div>
            <!-- /Danh sách vai trò -->


        </div>
        <!-- /right content -->

    </div>
@endsection
