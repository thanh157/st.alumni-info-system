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
     * Hiển thị trang báo cáo với 4 tabs
     */
    public function index(Request $request)
    {
        // Khởi tạo biến mặc định
        $studentTab2 = collect();
        $r1 = [];
        $survey = null;
        $schoolYear = null;
        $r1_trained_field = null;
        $r1_work_area = null;
        $r2 = collect();
        $alumniData = collect();

        // Kiểm tra nếu có survey_id được chọn
        if ($request->filled('survey_id')) {
            $survey = Survey::find($request->survey_id);

            if (!$survey) {
                abort(404, 'Không tìm thấy khảo sát');
            }

            // Lấy danh sách đợt tốt nghiệp
            $allDotTotNghiep = $survey->graduations()->get();
            $graduationIds = $survey->graduations()->pluck('id')->toArray();

            // Lấy danh sách student_id từ các đợt tốt nghiệp
            $studentIds = GraduationStudent::whereIn('graduation_id', $graduationIds)
                ->pluck('student_id')
                ->toArray();

            if (empty($studentIds)) {
                return view('admin.pages.admin.report', compact(
                    'survey',
                    'schoolYear',
                    'r1_trained_field',
                    'r1_work_area',
                    'studentTab2',
                    'r2',
                    'r1',
                    'alumniData'
                ));
            }

            // === TAB 2: Danh sách sinh viên tốt nghiệp ===
            $studentTab2 = Student::whereIn('id', $studentIds)->get();

            // === TAB 3: Danh sách sinh viên phản hồi về việc làm ===
            $r2 = EmploymentSurveyResponse::where('survey_period_id', $request->survey_id)->get();

            // === TAB 4: Thông tin cựu sinh viên từ bảng alumni_contact_surveys ===
            // Lấy mã sinh viên từ danh sách students
            $studentCodes = $studentTab2->pluck('code')->toArray();

            $alumniData = DB::table('alumni_contact_surveys')
                ->whereIn('student_code', $studentCodes)
                ->orderBy('created_at', 'desc')
                ->get();

            // === TAB 1: Báo cáo tổng hợp ===
            $schoolYear = $allDotTotNghiep->first()->school_year ?? '';

            // Tính toán số liệu tổng hợp
            $r1['total_student'] = $studentTab2->count();
            $r1['total_nu'] = $studentTab2->where('gender', 'female')->count();
            $r1['total_res'] = $r2->count();
            $r1['total_res_nu'] = $r2->where('gender', 'female')->count();

            // Thống kê theo ngành đào tạo
            $r1_trained_field = EmploymentSurveyResponse::selectRaw("
                SUM(CASE WHEN trained_field = 1 THEN 1 ELSE 0 END) AS dung_nganh,
                SUM(CASE WHEN trained_field = 2 THEN 1 ELSE 0 END) AS lien_quan,
                SUM(CASE WHEN trained_field = 3 THEN 1 ELSE 0 END) AS khong_lien_quan
            ")
                ->where('survey_period_id', $request->survey_id)
                ->first();

            // Thống kê theo khu vực làm việc
            $r1_work_area = EmploymentSurveyResponse::selectRaw("
                SUM(CASE WHEN work_area = '1' THEN 1 ELSE 0 END) AS nha_nuoc,
                SUM(CASE WHEN work_area = '2' THEN 1 ELSE 0 END) AS tu_nhan,
                SUM(CASE WHEN work_area = '3' THEN 1 ELSE 0 END) AS tu_tao,
                SUM(CASE WHEN work_area = '4' THEN 1 ELSE 0 END) AS nuoc_ngoai
            ")
                ->where('survey_period_id', $request->survey_id)
                ->first();
        }

        return view('admin.pages.admin.report', compact(
            'survey',
            'schoolYear',
            'r1_trained_field',
            'r1_work_area',
            'studentTab2',
            'r2',
            'r1',
            'alumniData'
        ));
    }

    /**
     * Export báo cáo ra file Excel theo type (tab1, tab2, tab3, tab4, all)
     */
    public function export(Request $request)
    {
        // Validate survey_id và type
        if (!$request->filled('survey_id')) {
            return back()->with('error', 'Vui lòng chọn một cuộc khảo sát để xuất báo cáo.');
        }

        $survey = Survey::find($request->survey_id);

        if (!$survey) {
            return back()->with('error', 'Không tìm thấy khảo sát.');
        }

        // Lấy type từ request (mặc định là 'all')
        $type = $request->get('type', 'all');

        // Lấy dữ liệu chung
        $allDotTotNghiep = $survey->graduations()->get();
        $graduationIds = $survey->graduations()->pluck('id')->toArray();
        $studentIds = GraduationStudent::whereIn('graduation_id', $graduationIds)
            ->pluck('student_id')
            ->toArray();

        if (empty($studentIds)) {
            return back()->with('error', 'Không có sinh viên nào trong đợt khảo sát này.');
        }

        $schoolYear = $allDotTotNghiep->first()->school_year ?? '';
        $studentTab2 = Student::whereIn('id', $studentIds)->get();
        $r2 = EmploymentSurveyResponse::where('survey_period_id', $request->survey_id)->get();

        // Tính toán dữ liệu Tab 1
        $r1 = [
            'total_student' => $studentTab2->count(),
            'total_nu' => $studentTab2->where('gender', 'female')->count(),
            'total_res' => $r2->count(),
            'total_res_nu' => $r2->where('gender', 'female')->count(),
        ];

        $r1_trained_field = EmploymentSurveyResponse::selectRaw("
            SUM(CASE WHEN trained_field = 1 THEN 1 ELSE 0 END) AS dung_nganh,
            SUM(CASE WHEN trained_field = 2 THEN 1 ELSE 0 END) AS lien_quan,
            SUM(CASE WHEN trained_field = 3 THEN 1 ELSE 0 END) AS khong_lien_quan
        ")
            ->where('survey_period_id', $request->survey_id)
            ->first();

        $r1_work_area = EmploymentSurveyResponse::selectRaw("
            SUM(CASE WHEN work_area = '1' THEN 1 ELSE 0 END) AS nha_nuoc,
            SUM(CASE WHEN work_area = '2' THEN 1 ELSE 0 END) AS tu_nhan,
            SUM(CASE WHEN work_area = '3' THEN 1 ELSE 0 END) AS tu_tao,
            SUM(CASE WHEN work_area = '4' THEN 1 ELSE 0 END) AS nuoc_ngoai
        ")
            ->where('survey_period_id', $request->survey_id)
            ->first();

        // Dữ liệu Tab 4
        $studentCodes = $studentTab2->pluck('code')->toArray();
        $alumniData = DB::table('alumni_contact_surveys')
            ->whereIn('student_code', $studentCodes)
            ->orderBy('created_at', 'desc')
            ->get();

        // Tên file theo type
        $fileNames = [
            'tab1' => 'mau-bao-cao-1',
            'tab2' => 'mau-bao-cao-2',
            'tab3' => 'mau-bao-cao-3',
            'tab4' => 'mau-bao-cao-4',
            'all' => 'bao-cao-tong-hop',
        ];

        $fileName = ($fileNames[$type] ?? 'bao-cao') . '-' . date('Y-m-d-His') . '.xlsx';

        // Export theo type
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