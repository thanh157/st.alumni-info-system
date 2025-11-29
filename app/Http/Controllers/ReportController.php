<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\EmploymentSurveyResponse;
use App\Models\Survey;
use App\Models\GraduationSurvey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\StudentService;
use Throwable;

class ReportController extends Controller
{
    public function __construct(private StudentService $studentService) {}

    /**
     * Lấy và cache token sinh viên
     */
    private function getStudentToken(): ?string
    {
        try {
            $user = auth()->user();
            if (!$user) {
                throw new \Exception('User chưa đăng nhập');
            }

            // Nếu đã có token thì dùng luôn
            if (!empty($user->st_students_token)) {
                return $user->st_students_token;
            }

            // Lấy token mới
            $tokenData = $this->studentService->getAccessTokenVerify();
            if (!$tokenData || empty($tokenData['token'])) {
                throw new \Exception('Không lấy được access token của sinh viên.');
            }

            $token = $tokenData['token'];

            // Lưu lại cho lần sau
            $user->update(['st_students_token' => $token]);

            return $token;
        } catch (Throwable $th) {
            Log::error('getStudentToken error: ' . $th->getMessage());
            return null;
        }
    }



    /**
     * Lấy danh sách sinh viên theo graduation IDs
     * API trả về: { "data": { "survey_students": [...] } }
     */
    private function fetchStudentsByGraduationIds(array $graduationIds): array
    {
        if (empty($graduationIds)) {
            return [];
        }

        $url = config('auth.student.ip') . '/api/v1/external/graduation-ceremonies/all-students';

        try {
            $token = $this->getStudentToken();
            if (empty($token)) {
                throw new \Exception('Không có token sinh viên.');
            }

            $response = Http::withToken($token)
                ->timeout(15)
                ->post($url, ['ids' => array_values($graduationIds)])
                ->json();

            // API trả về data trong field 'survey_students'
            if (!isset($response['data']['survey_students']) || !is_array($response['data']['survey_students'])) {
                Log::warning('API students trả về không hợp lệ', [
                    'response' => $response,
                    'graduation_ids' => $graduationIds,
                ]);
                throw new \Exception('API trả về dữ liệu không hợp lệ (students).');
            }

            return $response['data']['survey_students'];
        } catch (Throwable $th) {
            Log::error("fetchStudentsByGraduationIds error: " . $th->getMessage(), [
                'graduation_ids' => $graduationIds,
            ]);
            return [];
        }
    }

    /**
     * Lấy tất cả dữ liệu báo cáo
     * LUÔN trả về array, không bao giờ null
     */
    private function getReportData(int $surveyId): array
    {
        $survey = Survey::findOrFail($surveyId);

        // Lấy graduation IDs từ pivot table
        $graduationIds = GraduationSurvey::where('survey_id', $surveyId)
            ->pluck('graduation_id')
            ->toArray();

        // Khởi tạo giá trị mặc định
        $schoolYear = 'N/A';
        $facultyName = 'KHOA';
        $studentTab2 = collect();

        // Nếu có graduation IDs thì gọi API lấy sinh viên
        if (!empty($graduationIds)) {
            $studentsFromApi = $this->fetchStudentsByGraduationIds($graduationIds);
            
            $studentTab2 = collect($studentsFromApi)->map(function ($s) {
                return (object) $s;
            });

            // Lấy school_year và faculty từ sinh viên đầu tiên (nếu có)
            if ($studentTab2->isNotEmpty()) {
                $firstStudent = $studentTab2->first();
                
                // school_year từ school_year_end (ví dụ: "2020")
                $schoolYear = $firstStudent->school_year_end ?? 'N/A';
                
                // faculty_id có thể map sang tên khoa (hoặc lấy từ DB local)
                // Tạm thời để mặc định, có thể cải thiện sau
                $facultyName = 'KHOA';
            }
        }

        // Lấy dữ liệu phản hồi
        $r2 = EmploymentSurveyResponse::where('survey_period_id', $surveyId)->get();

        // Danh sách mã SV đã phản hồi
        $studentCodesResponded = $r2->pluck('code_student')->unique()->toArray();

        // Dữ liệu cựu sinh viên
        $alumniData = DB::table('alumni_contact_surveys')
            ->whereIn('student_code', $studentCodesResponded)
            ->orderBy('created_at', 'desc')
            ->get();

        Log::info('Report data fetched', [
            'survey_id' => $surveyId,
            'graduation_ids' => $graduationIds,
            'students_count' => $studentTab2->count(),
            'responses_count' => $r2->count(),
        ]);

        // Helper check giới tính nữ
        $isFemaleFn = function ($genderValue) {
            $gender = mb_strtolower($genderValue ?? '');
            return in_array($gender, ['nữ', 'nu', 'female', 'f', '0']);
        };

        // Tổng hợp chung
        $totalGraduates = (int) ($survey->total_graduations ?? 0);

        $totalNu = $studentTab2->filter(function ($s) use ($isFemaleFn) {
            return $isFemaleFn($s->gender ?? '');
        })->count();

        $r1 = [
            'total_student' => $totalGraduates,
            'total_nu' => $totalNu,
            'total_res' => $r2->count(),
            'total_res_nu' => $r2->filter(function ($s) use ($isFemaleFn) {
                return $isFemaleFn($s->gender ?? '');
            })->count(),
        ];

        // Thống kê theo ngành đào tạo
        $r1_trained_field = EmploymentSurveyResponse::selectRaw("
                SUM(CASE WHEN trained_field = 1 AND employment_status = 1 THEN 1 ELSE 0 END) AS dung_nganh,
                SUM(CASE WHEN trained_field = 2 AND employment_status = 1 THEN 1 ELSE 0 END) AS lien_quan,
                SUM(CASE WHEN trained_field = 3 AND employment_status = 1 THEN 1 ELSE 0 END) AS khong_lien_quan
            ")
            ->where('survey_period_id', $surveyId)
            ->first();

        // Thống kê theo khu vực làm việc
        $r1_work_area = EmploymentSurveyResponse::selectRaw("
                SUM(CASE WHEN work_area = '1' AND employment_status = 1 THEN 1 ELSE 0 END) AS nha_nuoc,
                SUM(CASE WHEN work_area = '2' AND employment_status = 1 THEN 1 ELSE 0 END) AS tu_nhan,
                SUM(CASE WHEN work_area = '3' AND employment_status = 1 THEN 1 ELSE 0 END) AS tu_tao,
                SUM(CASE WHEN work_area = '4' AND employment_status = 1 THEN 1 ELSE 0 END) AS nuoc_ngoai
            ")
            ->where('survey_period_id', $surveyId)
            ->first();

        // Thống kê chi tiết theo ngành
        $majorConfigs = config('survey.major_configs', [
            1 => ['code' => '7480201', 'name' => 'Công nghệ thông tin'],
            2 => ['code' => '7480102', 'name' => 'Mạng máy tính & Truyền dữ liệu'],
        ]);

        $r1Majors = [];

        foreach ($majorConfigs as $trainingIndustryId => $info) {
            $studentsMajor = $studentTab2->where('training_industry_id', $trainingIndustryId);
            $responsesMajor = $r2->where('training_industry_id', $trainingIndustryId);

            $totalStudentMajor = $studentsMajor->count();
            $totalNuMajor = $studentsMajor->filter(function ($s) use ($isFemaleFn) {
                return $isFemaleFn($s->gender ?? '');
            })->count();

            $totalResMajor = $responsesMajor->count();
            $totalResNuMajor = $responsesMajor->filter(function ($s) use ($isFemaleFn) {
                return $isFemaleFn($s->gender ?? '');
            })->count();

            $responsesEmployed = $responsesMajor->where('employment_status', 1);

            $dungNganh = $responsesEmployed->where('trained_field', 1)->count();
            $lienQuan = $responsesEmployed->where('trained_field', 2)->count();
            $khongLienQuan = $responsesEmployed->where('trained_field', 3)->count();

            $tiepTucHoc = $responsesMajor->where('employment_status', 2)->count();
            $chuaCoViec = $responsesMajor->where('employment_status', 3)->count();

            $nhaNuoc = $responsesEmployed->where('work_area', '1')->count();
            $tuNhan = $responsesEmployed->where('work_area', '2')->count();
            $tuTao = $responsesEmployed->where('work_area', '3')->count();
            $nuocNgoai = $responsesEmployed->where('work_area', '4')->count();

            $coViecLam = $dungNganh + $lienQuan + $khongLienQuan;

            $tyLeCoViecPhanHoi = $totalResMajor > 0
                ? round(($coViecLam / $totalResMajor) * 100, 2)
                : 0;

            $tyLeCoViecTotNghiep = $totalStudentMajor > 0
                ? round(($coViecLam / $totalStudentMajor) * 100, 2)
                : 0;

            $r1Majors[] = [
                'training_industry_id' => $trainingIndustryId,
                'major_code' => $info['code'],
                'major_name' => $info['name'],

                'total_student' => $totalStudentMajor,
                'total_nu' => $totalNuMajor,
                'total_res' => $totalResMajor,
                'total_res_nu' => $totalResNuMajor,

                'dung_nganh' => $dungNganh,
                'lien_quan' => $lienQuan,
                'khong_lien_quan' => $khongLienQuan,
                'tiep_tuc_hoc' => $tiepTucHoc,
                'chua_co_viec' => $chuaCoViec,

                'nha_nuoc' => $nhaNuoc,
                'tu_nhan' => $tuNhan,
                'tu_tao' => $tuTao,
                'nuoc_ngoai' => $nuocNgoai,

                'ty_le_co_viec_phan_hoi' => $tyLeCoViecPhanHoi,
                'ty_le_co_viec_tot_nghiep' => $tyLeCoViecTotNghiep,
            ];
        }

        // LUÔN return array (không bao giờ null)
        return compact(
            'survey',
            'schoolYear',
            'r1',
            'r1_trained_field',
            'r1_work_area',
            'studentTab2',
            'r2',
            'alumniData',
            'facultyName',
            'r1Majors'
        );
    }

    /**
     * Hiển thị trang báo cáo
     */
    public function index(Request $request)
    {
        // Giá trị mặc định
        $survey = null;
        $schoolYear = null;
        $r1 = [];
        $r1_trained_field = (object) [
            'dung_nganh' => 0,
            'lien_quan' => 0,
            'khong_lien_quan' => 0,
        ];
        $r1_work_area = (object) [
            'nha_nuoc' => 0,
            'tu_nhan' => 0,
            'tu_tao' => 0,
            'nuoc_ngoai' => 0,
        ];
        $studentTab2 = collect();
        $r2 = collect();
        $alumniData = collect();
        $facultyName = 'KHOA';
        $r1Majors = collect();

        if ($request->filled('survey_id')) {
            try {
                $data = $this->getReportData((int) $request->survey_id);

                // Gán dữ liệu từ kết quả
                $survey = $data['survey'];
                $schoolYear = $data['schoolYear'];
                $r1 = $data['r1'];
                $r1_trained_field = $data['r1_trained_field'];
                $r1_work_area = $data['r1_work_area'];
                $studentTab2 = $data['studentTab2'];
                $r2 = $data['r2'];
                $alumniData = $data['alumniData'];
                $facultyName = $data['facultyName'];
                $r1Majors = collect($data['r1Majors'] ?? []);

                Log::info('Report data loaded successfully', [
                    'survey_id' => $request->survey_id,
                    'students_count' => $studentTab2->count(),
                    'responses_count' => $r2->count(),
                ]);

            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                abort(404, 'Không tìm thấy khảo sát');
            } catch (\Exception $e) {
                Log::error('Error loading report data', [
                    'survey_id' => $request->survey_id,
                    'error' => $e->getMessage(),
                ]);
                return back()->with('error', 'Có lỗi xảy ra khi tải dữ liệu báo cáo.');
            }
        }

        return view('admin.pages.admin.report', compact(
            'survey',
            'schoolYear',
            'r1',
            'r1_trained_field',
            'r1_work_area',
            'studentTab2',
            'r2',
            'alumniData',
            'facultyName',
            'r1Majors'
        ));
    }

    /**
     * Export báo cáo ra Excel
     */
    public function export(Request $request)
    {
        if (!$request->filled('survey_id')) {
            return back()->with('error', 'Vui lòng chọn một cuộc khảo sát để xuất báo cáo.');
        }

        try {
            $data = $this->getReportData((int) $request->survey_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Không tìm thấy khảo sát.');
        } catch (\Exception $e) {
            Log::error('Error exporting report', [
                'survey_id' => $request->survey_id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Có lỗi xảy ra khi xuất báo cáo.');
        }

        $survey = $data['survey'];
        $schoolYear = $data['schoolYear'];
        $r1 = $data['r1'];
        $r1_trained_field = $data['r1_trained_field'];
        $r1_work_area = $data['r1_work_area'];
        $r2 = $data['r2'];
        $studentTab2 = $data['studentTab2'];
        $alumniData = $data['alumniData'];

        $type = $request->get('type', 'all');

        $fileNames = [
            'tab1' => 'mau-bao-cao-1',
            'tab2' => 'mau-bao-cao-2',
            'tab3' => 'mau-bao-cao-3',
            'tab4' => 'mau-bao-cao-4',
            'all' => 'bao-cao-tong-hop',
        ];

        $fileName = ($fileNames[$type] ?? 'bao-cao') . '-' . date('Y-m-d-His') . '.xlsx';

        return Excel::download(
            new ReportExport(
                $schoolYear,
                $r1,
                $r1_trained_field,
                $r1_work_area,
                $r2,
                $studentTab2,
                $alumniData,
                $type
            ),
            $fileName
        );
    }
}