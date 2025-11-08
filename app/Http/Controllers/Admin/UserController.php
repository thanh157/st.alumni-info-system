<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $users = User::query()
            ->search($request->keyword)
            ->with('userRoles')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('admin.pages.admin.user.index', compact('users'));
    }


    public function show($id, Request $request)
    {
        $roles = Role::query()
            ->search($request->keyword)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $user = User::findOrFail($id);
        $user->load('userRoles');
        return view('admin.pages.admin.user.show', compact('user', 'roles'));
    }

    public function updateRoles(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Gán lại danh sách vai trò (xóa cái cũ, thêm cái mới)
        $user->userRoles()->sync($request->role_ids ?? []);

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật vai trò thành công!');
    }
}
