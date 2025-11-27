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
        $r2 = EmploymentSurveyResponse::where('survey_period_id', $surveyId)->get();

        \Log::info('getReportData - r2 responses:', [
            'survey_id' => $surveyId,
            'r2_count' => $r2->count(),
            'r2_codes' => $r2->pluck('code_student')->toArray(),
        ]);

        if ($r2->isEmpty()) {
            \Log::warning('No employment responses found for survey', ['survey_id' => $surveyId]);
            return null; // Không có phản hồi
        }

        // 2. Lấy danh sách code sinh viên từ phản hồi
        $studentCodes = $r2->pluck('code_student')->unique()->toArray();

        // 3. Lấy danh sách sinh viên từ Student model theo code
        $studentTab2 = Student::whereIn('code', $studentCodes)->get();

        \Log::info('getReportData - studentTab2:', [
            'student_codes' => $studentCodes,
            'studentTab2_count' => $studentTab2->count(),
        ]);

        // 4. Dữ liệu Tab 4: Thông tin cựu sinh viên từ bảng alumni_contact_surveys
        $alumniData = DB::table('alumni_contact_surveys')
            ->whereIn('student_code', $studentCodes)
            ->orderBy('created_at', 'desc')
            ->get();

        // 5. Lấy danh sách đợt tốt nghiệp (nếu cần)
        $allGraduations = $survey->graduations()->get();
        $schoolYear = $allGraduations->first()->school_year ?? 'N/A';

        // 6. Tính toán Tab 1: Tổng hợp
        $r1 = [
            'total_student' => $studentTab2->count(),
            'total_nu' => $studentTab2->where('gender', 'female')->count(),
            'total_res' => $r2->count(),
            'total_res_nu' => $r2->where('gender', 'female')->count(),
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

        return compact(
            'survey',
            'schoolYear',
            'r1',
            'r1_trained_field',
            'r1_work_area',
            'studentTab2',
            'r2',
            'alumniData',
            'facultyName'
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

        if ($request->filled('survey_id')) {
            try {
                $data = $this->getReportData($request->survey_id);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                abort(404, 'Không tìm thấy khảo sát');
            }

            if ($data !== null) {
                // ✅ Gán trực tiếp từng biến từ mảng $data
                $survey = $data['survey'];
                $schoolYear = $data['schoolYear'];
                $r1 = $data['r1'];
                $r1_trained_field = $data['r1_trained_field'];
                $r1_work_area = $data['r1_work_area'];
                $studentTab2 = $data['studentTab2'];
                $r2 = $data['r2'];
                $alumniData = $data['alumniData'];
                $facultyName = $data['facultyName'];

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
            'facultyName'
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