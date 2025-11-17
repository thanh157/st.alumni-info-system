<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Models\Graduation;
use App\Models\GraduationSurvey;
use App\Services\StudentService;
use Illuminate\Support\Facades\Http;
use Throwable;

class SurveyController extends Controller
{
    public function __construct (private StudentService $studentService) {}

    public function index()
    {
        $data = Survey::with(['graduations'=>function( $query){
            $query->orderBy('name', 'desc');
        }
        , 'employmentSurveyResponse'])->orderBy('title', 'desc')->paginate(10);
        $viewData = [
            'data' => $data,
        ];
        return view('admin.pages.admin.survey.index', $viewData);
    }

    private function getAllGraduations()
    {
        $apiUrl = config('auth.student.ip') . '/api/v1/external/graduation-ceremonies/all';
        return $this->fetchApi($apiUrl);
    }
    
    private function getTotalSurveyStuents(array $graduationIds = [])
    {
        $apiUrl = config('auth.student.ip') . '/api/v1/external/graduation-ceremonies/total';
        $respone = $this->fetchApi($apiUrl, ['ids' => $graduationIds]);
        return $respone['survey_students'] ?? 0;
    }

    private function getAllSchoolYears()
    {
        $graduations = $this->getAllGraduations();
        $schoolYears = [];
        foreach ($graduations as $graduation) {
            if (isset($graduation['school_year']) && !in_array($graduation['school_year'], $schoolYears)) {
                $schoolYears[] = $graduation['school_year'];
            }
        }
        return $schoolYears;  
    }

    public function create()
    {
        $namTotNghiep = $this->getAllSchoolYears();
        $dotTotNghiep = $this->getAllGraduations();
        
        $viewData = [
            'namTotNghiep' => $namTotNghiep,
            'dotTotNghiep' => $dotTotNghiep,
        ];

        return view('admin.pages.admin.survey.create', $viewData);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'graduation_id' => 'required|array|min:1',
                'start_time' => 'required|date',
                'end_time' => 'bail|required|date|after_or_equal:start_time|after:now',
            ], [
                'end_time.after_or_equal' => 'Thời gian kết thúc không được trước thời gian bắt đầu.',
                'end_time.after' => 'Thời gian kết thúc phải lớn hơn hiện tại.',
                'graduation_id.required' => 'Vui lòng chọn ít nhất một đợt tốt nghiệp.',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }
            
            $total =$this->getTotalSurveyStuents($request->graduation_id);
            
            $survey = Survey::create([
                'title' => $request->title,
                'description' => $request->description,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => Survey::STATUS_ACTIVE,
                'total_graduations' => $total,
            ]);

            // Tạo liên kết đợt tốt nghiệp trong bảng pivot
            // Lưu các graduation_id vào bảng trung gian
            foreach ($request->graduation_id as $gradId) {
                GraduationSurvey::create([
                    'survey_id' => $survey->id,
                    'graduation_id' => $gradId,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.survey.index')->with('success', 'Tạo khảo sát thành công!');
        } catch (\Exception $e) {
            Log::error($e);
            DB::rollBack();
            return redirect()->route('admin.survey.index')->with('error', 'System error');
        }
    }

    // public function edit($id)
    // {
    //     $survey = Survey::with('graduations')->findOrFail($id);

    //     // Lấy danh sách năm tốt nghiệp duy nhất
    //     $namTotNghiep = Graduation::select('school_year')->distinct()->orderBy('school_year', 'desc')->pluck('school_year');

    //     // Lấy tất cả đợt tốt nghiệp
    //     $allDotTotNghiep = Graduation::orderBy('school_year', 'desc')->get();

    //     // Lấy các ID đợt tốt nghiệp đã được chọn
    //     $selectedGraduationIds = $survey->graduations->pluck('id')->toArray();

    //     return view('admin.pages.admin.survey.edit', compact(
    //         'survey',
    //         'namTotNghiep',
    //         'allDotTotNghiep',
    //         'selectedGraduationIds'
    //     ));
    // }

    public function edit($id)
    {
        $survey = Survey::findOrFail($id);

        // Lấy graduation_id từ pivot
        $selectedGraduationIds = GraduationSurvey::where('survey_id', $survey->id)
            ->pluck('graduation_id')
            ->toArray();

        // Lấy tất cả đợt tốt nghiệp từ API
        $allDotTotNghiep = $this->getAllGraduations(); // hàm từ controller cũ
        $namTotNghiep = $this->getAllSchoolYears();    // danh sách năm duy nhất

        return view('admin.pages.admin.survey.edit', compact(
            'survey',
            'namTotNghiep',
            'allDotTotNghiep',
            'selectedGraduationIds'
        ));
    }

    // public function update($id, Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $rules = [
    //             'title' => 'required|string|max:255',
    //             'description' => 'nullable|string',
    //             'start_time' => 'required|date',
    //             'end_time' => 'required|date|after_or_equal:start_time',
    //             'status' => 'required|in:0,1',
    //         ];

    //         // Nếu đang bật khảo sát → bắt buộc phải chọn đợt tốt nghiệp
    //         if ($request->status == 1) {
    //             $rules['graduation_id'] = 'required|array|min:1';
    //         }

    //         $validated = $request->validate($rules, [
    //             'graduation_id.required' => 'Vui lòng chọn ít nhất một đợt tốt nghiệp.',
    //             'end_time.after_or_equal' => 'Thời gian kết thúc không được trước thời gian bắt đầu.',
    //         ]);

    //         $survey = Survey::findOrFail($id);

    //         $survey->update([
    //             'title' => $request->title,
    //             'description' => $request->description,
    //             'start_time' => $request->start_time,
    //             'end_time' => $request->end_time,
    //             'status' => $request->status,
    //         ]);

    //         if ($request->status == 1) {
    //             $graduationIds = $request->graduation_id ?? $survey->graduations->pluck('id')->toArray();
    //             $survey->graduations()->sync($graduationIds);
    //         }

    //         DB::commit();
    //         return redirect()->route('admin.survey.index')->with('success', 'Cập nhật khảo sát thành công!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error($e);
    //         return redirect()->route('admin.survey.index')->with('error', 'Lỗi cập nhật khảo sát');
    //     }
    // }

    public function update($id, Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after_or_equal:start_time',
                'status' => 'required|in:0,1',
                'graduation_id' => 'required|array|min:1',
            ];

            $validated = $request->validate($rules, [
                'graduation_id.required' => 'Vui lòng chọn ít nhất một đợt tốt nghiệp.',
                'end_time.after_or_equal' => 'Thời gian kết thúc không được trước thời gian bắt đầu.',
            ]);

            $survey = Survey::findOrFail($id);
            $total =$this->getTotalSurveyStuents($request->graduation_id);
            $survey->update([
                'title' => $request->title,
                'description' => $request->description,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => $request->status,
                'total_graduations' => $total,
            ]);

            // Cập nhật pivot graduation_survey
            // Xoá các ID cũ
            GraduationSurvey::where('survey_id', $survey->id)->delete();

            // Thêm ID mới
            foreach ($request->graduation_id as $gradId) {
                GraduationSurvey::create([
                    'survey_id' => $survey->id,
                    'graduation_id' => $gradId,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.survey.index')->with('success', 'Cập nhật khảo sát thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            return redirect()->route('admin.survey.index')->with('error', 'Lỗi cập nhật khảo sát');
        }
    }

    public function destroy($id)
    {
        try {
            $survey = Survey::query()->findOrFail($id);

            // Xóa liên kết đợt tốt nghiệp trong pivot
            $survey->graduations()->detach();

            // Xóa khảo sát chính
            $survey->delete();

            return redirect()->route('admin.survey.index')->with('success', 'Đã xoá khảo sát thành công!');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->route('admin.survey.index')->with('error', 'Lỗi');
        }
    }

    public function showForm($id)
    {
        $survey = Survey::with('questions')->findOrFail($id);
        // Ép cast lại từng câu hỏi nếu cần
        $survey->questions->transform(function ($q) {
            $q->options = is_string($q->options) ? json_decode($q->options, true) : $q->options;
            return $q;
        });

        $viewData = [
            'survey' => $survey
        ];
        return view('admin.pages.admin.survey.form', $viewData);
    }

    // public function getDotTotNghiep()
    // {
    //     $nam = request('school_year');
    //     $data = Graduation::query()->where('school_year', $nam)->get();
    //     return response()->json($data);
    // }

    private function fetchApi(string $url, array $params = []): array
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
            $response = Http::withToken($user->st_students_token)
                ->timeout(10)
                ->get($url, $params)
                ->json();

            if (!isset($response['data'])) {
                throw new \Exception('API trả về dữ liệu không hợp lệ.');
            }
            return $response['data'];
           
        } catch (Throwable $th) {
            Log::error("getAllGraduations error: " . $th->getMessage());
            return [];
        }
    }
}
