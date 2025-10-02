<?php

namespace App\Http\Controllers;

use App\Exports\SurveyExport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\StudentService;
use Illuminate\Support\Arr;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct(private StudentService $studentService) {}

    public function index(Request $request)
    {
        // =================================================================
        // PHẦN 1: LẤY FILTER VÀ DỮ LIỆU PHỤ (GIỮ NGUYÊN)
        // =================================================================
        $facultyId = $this->studentService->getFacultyId();
        $surveyId = $request->input('survey_id');
        $selectedGraduationId = $request->input('graduation_id');

        // (Code lấy token, danh sách đợt tốt nghiệp, danh sách ngành học của bạn giữ nguyên)
        // ...
        $graduations = collect([]); // Giả sử đã lấy được $graduations
        $industries = collect([]);  // Giả sử đã lấy được $industries

        // =================================================================
        // PHẦN 2: TRUY VẤN TỔNG HỢP DỮ LIỆU BÁO CÁO (VIẾT LẠI)
        // =================================================================
        $reportQuery = DB::table('graduation as g')
            ->join('graduation_student as gs', 'g.id', '=', 'gs.graduation_id')
            ->join('student as s', 'gs.student_id', '=', 's.id')
            ->leftJoin('employment_survey_responses_v2 as esr', 's.code', '=', 'esr.code_student')
            ->leftJoin('training_industries as ti', 's.training_industry_id', '=', 'ti.id') // Join để lấy tên ngành
            ->select(
                'ti.name as ten_nganh',
                'ti.code as training_industry_id',
                DB::raw('COUNT(DISTINCT s.id) as sv_tot_nghiep'),
                DB::raw('COUNT(DISTINCT CASE WHEN s.gender = "female" THEN s.id END) as sv_nu_tot_nghiep'),
                DB::raw('COUNT(esr.id) as tong_phan_hoi'),
                DB::raw('COUNT(CASE WHEN esr.gender = "female" THEN esr.id END) as nu_phan_hoi'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 THEN 1 ELSE 0 END) as co_viec_lam'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 2 THEN 1 ELSE 0 END) as tiep_tuc_hoc'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 3 THEN 1 ELSE 0 END) as chua_co_viec'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field IN (1, 2) THEN 1 ELSE 0 END) as viec_lam_lien_quan'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 3 THEN 1 ELSE 0 END) as viec_lam_khong_lien_quan'),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '1' THEN 1 ELSE 0 END) as lam_viec_nha_nuoc"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '2' THEN 1 ELSE 0 END) as lam_viec_tu_nhan"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '3' THEN 1 ELSE 0 END) as tu_tao_viec_lam"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '4' THEN 1 ELSE 0 END) as yeu_to_nuoc_ngoai")
            )
            ->groupBy('ti.name', 'ti.code')
            ->orderBy('ti.name');

        // Áp dụng filter vào câu truy vấn
        if ($surveyId) {
            $reportQuery->where('esr.survey_period_id', $surveyId);
        }
        if ($selectedGraduationId) {
            $reportQuery->where('g.id', $selectedGraduationId);
        }

        $report1 = $reportQuery->get()->map(function ($row) {
            // Tính toán các tỷ lệ %
            $row->ty_le_co_viec_phan_hoi = $row->tong_phan_hoi > 0 ? round($row->co_viec_lam / $row->tong_phan_hoi * 100, 2) : 0;
            $row->ty_le_co_viec_tot_nghiep = $row->sv_tot_nghiep > 0 ? round($row->co_viec_lam / $row->sv_tot_nghiep * 100, 2) : 0;
            return $row;
        });

        // =================================================================
        // PHẦN 3: TRUY VẤN DANH SÁCH SINH VIÊN CHI TIẾT (NẾU CẦN)
        // =================================================================
        // Phần này có thể được tối ưu tương tự nếu cần hiển thị bảng danh sách sinh viên chi tiết
        $students = collect([]); // Tạm thời để trống hoặc viết query tương tự

        // =================================================================
        // PHẦN 4: TRẢ VỀ VIEW
        // =================================================================
        return view('admin.pages.admin.report', [
            'report1' => $report1,
            'students' => $students,
            'graduations' => $graduations,
            // ... các biến khác
            'surveyId' => $surveyId,
            'graduationId' => $selectedGraduationId,
        ]);
    }

    public function exportSurvey(Request $request)
    {
        $surveyId = $request->input('survey_id');
        $graduationId = $request->input('graduation_id');
        $fileName = 'bao_cao_khao_sat_' . Carbon::now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new SurveyExport($surveyId, $graduationId), $fileName);
    }
}
