<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\DotTotnghiep;
use App\Models\DotTotNghiepStudent;
use App\Models\GraduationStudent;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Graduation;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class GraduationController extends Controller
{
    public function __construct (private StudentService $studentService) {}

    public function index(Request $request)
    {
        try {
            $user = auth()->user();

            // Lấy access token nếu chưa có
            if (empty($user->st_students_token)) {
                $tokenData = $this->studentService->getAccessTokenVerify();
                if (!$tokenData || empty($tokenData['token'])) {
                    throw new \Exception('Không lấy được access token của sinh viên.');
                }
            }

            // Gọi API
            $apiUrl = config('auth.student.ip') . '/api/v1/external/graduation-ceremonies';
            $response = Http::withToken($user->st_students_token)
                ->timeout(10)
                ->get($apiUrl, [
                    'page' => $request->get('page', 1),
                ])
                ->json();

            if (!isset($response['data'])) {
                throw new \Exception('API trả về dữ liệu không hợp lệ.');
            }

            $graduations = collect($response['data']);
            $meta = $response['meta'] ?? [];
            $total = $meta['total'] ?? $graduations->count();
            $perPage = $meta['per_page'] ?? 10;
            $currentPage = $meta['current_page'] ?? 1;

            // Lưu DB (chỉ nếu cần)
            foreach ($graduations as $item) {
                Graduation::updateOrCreate(
                    ['id' => data_get($item, 'id')],
                    [
                        'name' => data_get($item, 'name'),
                        'school_year' => data_get($item, 'school_year'),
                        'certification' => data_get($item, 'certification'),
                        'certification_date' => Carbon::parse(data_get($item, 'certification_date'))->toDateString(),
                        'faculty_id' => data_get($item, 'faculty_id'),
                        'student_count' => data_get($item, 'student_count'),
                        'created_at' => Carbon::parse(data_get($item, 'created_at')),
                        'updated_at' => Carbon::parse(data_get($item, 'updated_at')),
                    ]
                );
            }

            // Lọc dữ liệu (nếu có)
            if ($request->filled('name')) {
                $graduations = $graduations->filter(fn($g) =>
                    str_contains(Str::lower($g['name']), Str::lower($request->input('name')))
                );
            }

            if ($request->filled('year')) {
                $graduations = $graduations->filter(fn($g) =>
                    str_contains(Str::lower($g['school_year']), Str::lower($request->input('year')))
                );
            }

            // Sắp xếp
            $graduations = match ($request->input('sap_xep')) {
                'moi_nhat' => $graduations->sortByDesc('created_at'),
                'cu_nhat' => $graduations->sortBy('created_at'),
                default => $graduations,
            };

            // Phân trang dựa trên meta từ API
            $graduationsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $graduations,
                $total,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('admin.pages.admin.graduation', [
                'graduations' => $graduationsPaginated,
                'showPaginationInfo' => $total > $perPage,
            ]);

        } catch (Throwable $th) {
            Log::error('GraduationController@index error', [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);

            return back()->with('error', 'Không thể tải dữ liệu đợt tốt nghiệp.');
        }
    }

    public function showStudents(Request $request, $graduationId)
    {
        try {
            $graduationName = base64_decode($request->query('name'));
            $user = auth()->user();
    
            // Lấy access token nếu chưa có
            if (empty($user->st_students_token)) {
                $tokenData = $this->studentService->getAccessTokenVerify();
                if (!$tokenData || empty($tokenData['token'])) {
                    throw new \Exception('Không lấy được access token của sinh viên.');
                }
            }
    
            // Gọi API lấy danh sách sinh viên theo đợt TN
            $apiUrl = config('auth.student.ip') . "/api/v1/external/graduation-ceremonies/{$graduationId}/students";
    
            $response = Http::withToken($user->st_students_token)
                ->timeout(10)
                ->get($apiUrl, [
                    'page'  => $request->get('page', 1),
                ])
                ->json();
    
            if (!isset($response['data'])) {
                throw new \Exception('API trả về dữ liệu không hợp lệ.');
            }
    
            // Dữ liệu sinh viên từ API
            $students = collect($response['data']);
    
            // Pagination metadata từ API
            $meta = $response['meta'] ?? [];
            $total = $meta['total'] ?? $students->count();
            $perPage = $meta['per_page'] ?? 10;
            $currentPage = $meta['current_page'] ?? 1;
    
            // Phân trang theo meta API
            $studentsPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
                $students,
                $total,
                $perPage,
                $currentPage,
                ['path' => $request->url(), 'query' => $request->query()]
            );
    
            return view('admin.pages.admin.graduation-students', [
                'students' => $studentsPaginated,
                'graduationName' => $graduationName,
                'showPaginationInfo' => $total > $perPage,
            ]);
    
        } catch (Throwable $th) {
            Log::error("GraduationController@showStudents error", [
                'message' => $th->getMessage(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
            ]);
    
            return back()->with('error', 'Không thể tải dữ liệu sinh viên.');
        }
    }

    // public function create()
    // {
    //     return view('admin.pages.admin.graduation-create');
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'dot_tot_nghiep' => 'required|string',
    //         'nam_tot_nghiep' => 'required|string',
    //         'tong_sinh_vien' => 'required|numeric',
    //     ]);

    //     $path = base_path('app/JSON/Graduation.json');
    //     $graduations = File::exists($path) ? json_decode(File::get($path), true) : [];

    //     $newId = collect($graduations)->max('id') + 1;

    //     $data = [
    //         'id' => $newId,
    //         'dot_tot_nghiep' => $request->dot_tot_nghiep,
    //         'nam_tot_nghiep' => $request->nam_tot_nghiep,
    //         'tong_sinh_vien' => $request->tong_sinh_vien,
    //         'created_at' => now()->format('Y-m-d H:i:s'), // đảm bảo giờ đúng
    //     ];

    //     $graduations[] = $data;

    //     File::put($path, json_encode($graduations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    //     return redirect()->route('admin.graduation.index')->with('success', 'Thêm đợt tốt nghiệp thành công!');
    // }

    // public function edit($id)
    // {
    //     $path = base_path('app/JSON/Graduation.json');
    //     $graduations = File::exists($path) ? json_decode(File::get($path), true) : [];

    //     $item = collect($graduations)->firstWhere('id', (int) $id);
    //     if (!$item) return redirect()->route('admin.graduation.index')->with('error', 'Không tìm thấy đợt tốt nghiệp.');

    //     return view('admin.pages.admin.graduation-edit', compact('item'));
    // }

    // public function destroy($id)
    // {
    //     $path = base_path('app/JSON/Graduation.json');
    //     $graduations = File::exists($path) ? json_decode(File::get($path), true) : [];

    //     $graduations = array_filter($graduations, fn($item) => $item['id'] != $id);
    //     File::put($path, json_encode(array_values($graduations), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    //     return redirect()->route('admin.graduation.index')->with('success', 'Đã xoá đợt tốt nghiệp.');
    // }


    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'dot_tot_nghiep' => 'required|string',
    //         'nam_tot_nghiep' => 'required|string',
    //         'tong_sinh_vien' => 'required|numeric',
    //     ]);

    //     $path = base_path('app/JSON/Graduation.json');
    //     $graduations = File::exists($path) ? json_decode(File::get($path), true) : [];

    //     foreach ($graduations as &$item) {
    //         if ($item['id'] == $id) {
    //             $item['dot_tot_nghiep'] = $request->input('dot_tot_nghiep');
    //             $item['nam_tot_nghiep'] = $request->input('nam_tot_nghiep');
    //             $item['tong_sinh_vien'] = $request->input('tong_sinh_vien');
    //             $item['updated_at'] = now()->format('Y-m-d H:i:s');
    //             break;
    //         }
    //     }

    //     File::put($path, json_encode($graduations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    //     return redirect()->route('admin.graduation.index')->with('success', 'Sửa đợt tốt nghiệp thành công!');
    // }
}
