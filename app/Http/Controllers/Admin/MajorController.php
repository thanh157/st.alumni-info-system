<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Major;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class MajorController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    public function index(Request $request)
    {
        //  Lấy ID của major
        $facultyId = $this->studentService->getFacultyId();
        // lấy token (không cần đăng nhập, chỉ cần client_id và client_secret).
        $token = cache()->remember('token_client1', 300, function () {
            return $this->studentService->post('/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => config('auth.student.client_id'),
                'client_secret' => config('auth.student.client_secret'),
            ]);
        });

        $response = $this->studentService->get("/api/v1/external/training-industries/faculty/{$facultyId}", [
            // Gửi kèm access_token để xác thực API.
            'access_token' => Arr::get($token, 'access_token'),
        ]);

        //         Nếu API không trả về data thì dùng mảng rỗng [] để tránh lỗi.

        // .map(...): lặp qua từng ngành, và nếu status chưa có thì gán mặc định là 'active'.
        $majors = collect($response['data'] ?? [])->map(function ($major) {
            $major['status'] = $major['status'] ?? 'active'; // mặc định
            return $major;
        });

        // Filter
        $tenNganh = $request->query('ten_nganh');
        $maNganh = $request->query('ma_nganh');
        $trangThai = $request->query('trang_thai');
        $sapXep = $request->query('sap_xep');

        if ($tenNganh) {
            $majors = $majors->filter(function ($item) use ($tenNganh) {
                return Str::contains(Str::lower($item['name']), Str::lower($tenNganh));
            });
        }

        if ($maNganh) {
            $majors = $majors->filter(function ($item) use ($maNganh) {
                return Str::contains(Str::lower($item['code']), Str::lower($maNganh));
            });
        }

        if ($trangThai) {
            $majors = $majors->where('status', $trangThai);
        }

        if ($sapXep === 'moi_nhat') {
            $majors = $majors->sortByDesc('created_at');
        } elseif ($sapXep === 'cu_nhat') {
            $majors = $majors->sortBy('created_at');
        }

        foreach ($majors->values() as $item) {
            Major::query()->updateOrCreate(
                [
                    'code' => $item['code']
                ],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'status' => $item['status'] == 'active' ? Major::STATUS_ACTIVE : Major::STATUS_INACTIVE
                ]
            );
        }

        return view('admin.pages.admin.major', [
            'majors' => $majors->values(),
        ]);
    }
}
