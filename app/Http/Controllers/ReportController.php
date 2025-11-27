<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\EmploymentSurveyResponse;
use App\Models\GraduationStudent;
use App\Models\Student;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Lấy tất cả dữ liệu báo cáo cần thiết cho cả hiển thị và xuất khẩu.
     *
     * @param int $surveyId
     * @return array|null
     */
    private function getReportData(int $surveyId): ?array
    {
        // Sử dụng findOrFail để tự động throw 404 nếu không tìm thấy Survey
        $survey = Survey::findOrFail($surveyId);

        // 1. Lấy dữ liệu Tab 3: Danh sách sinh viên phản hồi về việc làm từ EmploymentSurveyResponse
        // 1. Lấy dữ liệu Tab 3: Danh sách sinh viên phản hồi về việc làm từ EmploymentSurveyResponse
        $r2 = EmploymentSurveyResponse::where('survey_period_id', $surveyId)->get();

        if ($r2->isEmpty()) {
            \Log::warning('No employment responses found for survey', ['survey_id' => $surveyId]);
            return null; // Không có phản hồi
        }

        // 2. Lấy các đợt tốt nghiệp gắn với survey
            $allGraduations = $survey->graduations()->get();
            $schoolYear = $allGraduations->first()->school_year ?? 'N/A';

            $graduationIds = $allGraduations->pluck('id');

            // 3. Từ bảng graduation_student -> lấy TOÀN BỘ sinh viên tốt nghiệp
            $graduationStudent = GraduationStudent::whereIn('graduation_id', $graduationIds)->get();
            $studentIds = $graduationStudent->pluck('student_id')->unique();

            // 4. Lấy danh sách sinh viên từ Student theo ID (TAB 2 ĐÚNG)
            $studentTab2 = Student::whereIn('id', $studentIds)->get();

            // 5. Danh sách mã SV đã phản hồi (chỉ để check phản hồi, KHÔNG dùng để lọc student)
            $studentCodesResponded = $r2->pluck('code_student')->unique()->toArray();

            \Log::info('TAB 2 - ALL GRADUATED STUDENTS', [
                'graduation_ids' => $graduationIds->toArray(),
                'student_ids_count' => $studentIds->count(),
                'studentTab2_count' => $studentTab2->count(),
                'studentCodesResponded_count' => count($studentCodesResponded),
            ]);

        // 5. Lấy danh sách đợt tốt nghiệp (nếu cần)
        $allGraduations = $survey->graduations()->get();
        $schoolYear = $allGraduations->first()->school_year ?? 'N/A';

        // 6. Tính toán Tab 1: Tổng hợp chung
        $totalGraduates = (int) ($survey->total_graduations ?? 0);

            $totalNu = $studentTab2->filter(function ($s) {
                return in_array(mb_strtolower($s->gender), ['nữ', 'female']);
            })->count();

            $r1 = [
                'total_student'   => $totalGraduates,   
                'total_nu'        => $totalNu,           
                'total_res'       => $r2->count(),
                'total_res_nu'    => $r2->filter(function ($s) {
                    return in_array(mb_strtolower($s->gender), ['nữ', 'female']);
                })->count(),
            ];

        // 7. Thống kê theo ngành đào tạo (trained_field)
        // Chỉ tính những người CÓ VIỆC LÀM (employment_status = 1)
        $r1_trained_field = DB::table('employment_survey_responses_v2')
            ->selectRaw("
                SUM(CASE WHEN trained_field = 1 AND employment_status = 1 THEN 1 ELSE 0 END) AS dung_nganh,
                SUM(CASE WHEN trained_field = 2 AND employment_status = 1 THEN 1 ELSE 0 END) AS lien_quan,
                SUM(CASE WHEN trained_field = 3 AND employment_status = 1 THEN 1 ELSE 0 END) AS khong_lien_quan
            ")
            ->where('survey_period_id', $surveyId)
            ->first();

        // 8. Thống kê theo khu vực làm việc (work_area)
        // Chỉ tính những người CÓ VIỆC LÀM (employment_status = 1)
        $r1_work_area = DB::table('employment_survey_responses_v2')
            ->selectRaw("
                SUM(CASE WHEN work_area = '1' AND employment_status = 1 THEN 1 ELSE 0 END) AS nha_nuoc,
                SUM(CASE WHEN work_area = '2' AND employment_status = 1 THEN 1 ELSE 0 END) AS tu_nhan,
                SUM(CASE WHEN work_area = '3' AND employment_status = 1 THEN 1 ELSE 0 END) AS tu_tao,
                SUM(CASE WHEN work_area = '4' AND employment_status = 1 THEN 1 ELSE 0 END) AS nuoc_ngoai
            ")
            ->where('survey_period_id', $surveyId)
            ->first();

        // 9. Lấy tên khoa từ sinh viên đầu tiên (qua relation faculty)
        $facultyName = 'KHOA';
        if ($studentTab2->isNotEmpty() && $studentTab2->first()->faculty) {
            $facultyName = $studentTab2->first()->faculty->name ?? 'KHOA';
        } elseif ($allGraduations->isNotEmpty() && $allGraduations->first()->faculty) {
            $facultyName = $allGraduations->first()->faculty->name ?? 'KHOA';
        }

        // 10. Thống kê chi tiết theo 2 ngành cố định
        // Map 1 → 7480201 – CNTT
        // Map 2 → 7480102 – MMT&TTDL
        $majorConfigs = [
            1 => ['code' => '7480201', 'name' => 'Công nghệ thông tin'],
            2 => ['code' => '7480102', 'name' => 'Mạng máy tính & Truyền dữ liệu'],
        ];

        $r1Majors = [];

        foreach ($majorConfigs as $trainingIndustryId => $info) {
            // Sinh viên thuộc ngành này (trong danh sách Tab2)
            $studentsMajor = $studentTab2->where('training_industry_id', $trainingIndustryId);
            // Phản hồi thuộc ngành này
            $responsesMajor = $r2->where('training_industry_id', $trainingIndustryId);

            $totalStudentMajor = $studentsMajor->count();
            $totalNuMajor = $studentsMajor->where('gender', 'Nữ')->count();
            $totalResMajor = $responsesMajor->count();
            $totalResNuMajor = $responsesMajor->where('gender', 'Nữ')->count();

            // Chỉ những người có việc làm
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

        return compact(
            'survey',
            'schoolYear',
            'r1',
            'r1_trained_field',
            'r1_work_area',
            'studentTab2',
            'r2',
            'facultyName',
            'r1Majors'
        );
    }

    /**
     * Hiển thị trang báo cáo với 4 tabs
     */
    public function index(Request $request)
    {
        // Khởi tạo các biến với giá trị mặc định
        $survey = null;
        $schoolYear = null;
        $r1 = [];
        $r1_trained_field = (object) [
            'dung_nganh' => 0,
            'lien_quan' => 0,
            'khong_lien_quan' => 0
        ];
        $r1_work_area = (object) [
            'nha_nuoc' => 0,
            'tu_nhan' => 0,
            'tu_tao' => 0,
            'nuoc_ngoai' => 0
        ];
        $studentTab2 = collect();
        $r2 = collect();
        $alumniData = collect();
        $facultyName = 'KHOA';
        $r1Majors = collect(); // thêm

        if ($request->filled('survey_id')) {
            try {
                $data = $this->getReportData($request->survey_id);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                abort(404, 'Không tìm thấy khảo sát');
            }

            if ($data !== null) {
                $survey = $data['survey'];
                $schoolYear = $data['schoolYear'];
                $r1 = $data['r1'];
                $r1_trained_field = $data['r1_trained_field'];
                $r1_work_area = $data['r1_work_area'];
                $studentTab2 = $data['studentTab2'];
                $r2 = $data['r2'];
                 $facultyName = $data['facultyName'];
                $r1Majors = collect($data['r1Majors'] ?? []);

                \Log::info('ReportController Data Assigned:', [
                    'r1' => $r1,
                    'studentTab2_count' => $studentTab2->count(),
                    'r2_count' => $r2->count(),
                    'facultyName' => $facultyName,
                ]);
            } else {
                // Trường hợp survey có tồn tại nhưng không có phản hồi
                $survey = Survey::find($request->survey_id);
                \Log::warning('Survey found but no responses', ['survey_id' => $request->survey_id]);
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
     * Export báo cáo ra file Excel theo type (tab1, tab2, tab3, tab4, all)
     */
    public function export(Request $request)
    {
        // 1. Validate survey_id
        if (!$request->filled('survey_id')) {
            return back()->with('error', 'Vui lòng chọn một cuộc khảo sát để xuất báo cáo.');
        }

        // 2. Lấy dữ liệu báo cáo
        try {
            $data = $this->getReportData($request->survey_id);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->with('error', 'Không tìm thấy khảo sát.');
        }

        if ($data === null) {
            return back()->with('error', 'Không có phản hồi nào trong đợt khảo sát này.');
        }

        // 3. Gán các biến từ kết quả
        $survey = $data['survey'];
        $schoolYear = $data['schoolYear'];
        $r1 = $data['r1'];
        $r1_trained_field = $data['r1_trained_field'];
        $r1_work_area = $data['r1_work_area'];
        $r2 = $data['r2'];
        $studentTab2 = $data['studentTab2'];
        $alumniData = $data['alumniData'];

        // 4. Lấy type từ request (mặc định là 'all')
        $type = $request->get('type', 'all');

        // 5. Tên file theo type
        $fileNames = [
            'tab1' => 'mau-bao-cao-1',
            'tab2' => 'mau-bao-cao-2',
            'tab3' => 'mau-bao-cao-3',
            'tab4' => 'mau-bao-cao-4',
            'all' => 'bao-cao-tong-hop',
        ];

        $fileName = ($fileNames[$type] ?? 'bao-cao') . '-' . date('Y-m-d-His') . '.xlsx';

        // 6. Export
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
