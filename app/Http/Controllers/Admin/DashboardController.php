<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang Dashboard với 4 KPI 
     */
    public function index(): View
    {
        // --- 1. TỔNG SỐ SV TỐT NGHIỆP (Lấy tổng từ bảng survey_periods) ---
        $totalGraduates = (int) DB::table('survey')->sum('total_graduations');

        // --- 2. TỔNG SỐ PHẢN HỒI KHẢO SÁT ---
        $totalResponses = (int) DB::table('employment_survey_responses_v2')->count();

        // --- 3. SỐ LƯỢNG CÓ VIỆC LÀM (Chỉ tính Status = 1) ---
        $employedCount = DB::table('employment_survey_responses_v2')
            ->where('employment_status', 1)
            ->count();

        // --- 4. SỐ LƯỢNG VIỆC LÀM PHÙ HỢP (KPI Quan trọng) ---
        // Status 1=Có việc, 3=Học tiếp. Field 1=Đúng ngành, 2=Liên quan
        $relevantCount = DB::table('employment_survey_responses_v2')
            ->where(function ($query) {
                $query->where('employment_status', 1)
                    ->whereIn('trained_field', [1, 2]);
            })
            ->orWhere('employment_status', 3)
            ->count();


        // KPI 1: Tỷ lệ phản hồi
        $kpi1_ResponseRate = $totalGraduates > 0
            ? round(($totalResponses / $totalGraduates) * 100, 2) : 0;

        // KPI 2: Tỷ lệ có việc làm / Số phản hồi
        $kpi2_EmployedPerRes = $totalResponses > 0
            ? round(($employedCount / $totalResponses) * 100, 2) : 0;

        // KPI 3: Tỷ lệ có việc làm / Tổng SV tốt nghiệp
        $kpi3_EmployedPerGrad = $totalGraduates > 0
            ? round(($employedCount / $totalGraduates) * 100, 2) : 0;

        // KPI 4: Tỷ lệ việc làm phù hợp / Tổng SV tốt nghiệp
        $kpi4_RelevantPerGrad = $totalGraduates > 0
            ? round(($relevantCount / $totalGraduates) * 100, 2) : 0;

        return view('admin.pages.admin.dashboard', compact(
            'totalResponses',
            'totalGraduates',
            'kpi1_ResponseRate',
            'kpi2_EmployedPerRes',
            'kpi3_EmployedPerGrad',
            'kpi4_RelevantPerGrad'
        ));
    }

    /**
     * API trả dữ liệu JSON cho Biểu đồ (Chart)
     */
    public function getChartData(): JsonResponse
    {
        $rows = DB::table('employment_survey_responses_v2 as esr')
            ->leftJoin('survey_periods as sp', 'esr.survey_period_id', '=', 'sp.id')
            ->select(
                'esr.survey_period_id',
                'sp.title as period_name',

                // --- NHÓM 1: CŨ (Giữ lại để tương thích) ---
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 THEN 1 ELSE 0 END) as employed_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status != 1 AND esr.employment_status != 3 THEN 1 ELSE 0 END) as unemployed_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field IN (1,2) THEN 1 ELSE 0 END) as related_field_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 3 THEN 1 ELSE 0 END) as unrelated_field_count'),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area IN ('1','2','3') THEN 1 ELSE 0 END) as domestic_count"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '4' THEN 1 ELSE 0 END) as foreign_count"),

                // --- NHÓM 2: CHI TIẾT 5 LOẠI TÌNH HÌNH VIỆC LÀM (MỚI) ---
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 1 THEN 1 ELSE 0 END) as s1_dung_nganh'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 2 THEN 1 ELSE 0 END) as s2_lien_quan'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 3 THEN 1 ELSE 0 END) as s3_trai_nganh'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 3 THEN 1 ELSE 0 END) as s4_hoc_tiep'),
                DB::raw('SUM(CASE WHEN esr.employment_status != 1 AND esr.employment_status != 3 THEN 1 ELSE 0 END) as s5_chua_co_viec'),

                // --- NHÓM 3: CHI TIẾT 4 KHU VỰC LÀM VIỆC (MỚI) ---
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '1' THEN 1 ELSE 0 END) as a1_nha_nuoc"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '2' THEN 1 ELSE 0 END) as a2_tu_nhan"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '3' THEN 1 ELSE 0 END) as a3_tu_tao"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '4' THEN 1 ELSE 0 END) as a4_nuoc_ngoai")
            )
            ->groupBy('esr.survey_period_id', 'sp.title')
            ->orderBy('esr.survey_period_id')
            ->get();

        // Khởi tạo biến tổng
        $totals = [
            'employed' => 0, 'unemployed' => 0,
            'related' => 0, 'unrelated' => 0,
            'domestic' => 0, 'foreign' => 0,
            // Biến tổng mới
            's1' => 0, 's2' => 0, 's3' => 0, 's4' => 0, 's5' => 0,
            'a1' => 0, 'a2' => 0, 'a3' => 0, 'a4' => 0,
        ];

        $bar = [];

        foreach ($rows as $r) {
            $termName = $r->period_name ?? ('Đợt ' . $r->survey_period_id);
            if (preg_match('/(20\d{2})/', $termName, $matches)) {
                $termName = 'Năm ' . $matches[1];
            }

            $item = ['term' => $termName];

            // 1. Nhóm cũ
            $item['employed'] = (int) $r->employed_count; $totals['employed'] += $item['employed'];
            $item['unemployed'] = (int) $r->unemployed_count; $totals['unemployed'] += $item['unemployed'];
            
            $item['domestic'] = (int) $r->domestic_count; $totals['domestic'] += $item['domestic'];
            $item['foreign'] = (int) $r->foreign_count; $totals['foreign'] += $item['foreign'];
            
            $item['related'] = (int) $r->related_field_count; $totals['related'] += $item['related'];
            $item['unrelated'] = (int) $r->unrelated_field_count; $totals['unrelated'] += $item['unrelated'];

            // 2. Nhóm mới: Trạng thái việc làm (s1 - s5)
            $item['s1'] = (int) $r->s1_dung_nganh; $totals['s1'] += $item['s1'];
            $item['s2'] = (int) $r->s2_lien_quan; $totals['s2'] += $item['s2'];
            $item['s3'] = (int) $r->s3_trai_nganh; $totals['s3'] += $item['s3'];
            $item['s4'] = (int) $r->s4_hoc_tiep; $totals['s4'] += $item['s4'];
            $item['s5'] = (int) $r->s5_chua_co_viec; $totals['s5'] += $item['s5'];

            // 3. Nhóm mới: Khu vực (a1 - a4)
            $item['a1'] = (int) $r->a1_nha_nuoc; $totals['a1'] += $item['a1'];
            $item['a2'] = (int) $r->a2_tu_nhan; $totals['a2'] += $item['a2'];
            $item['a3'] = (int) $r->a3_tu_tao; $totals['a3'] += $item['a3'];
            $item['a4'] = (int) $r->a4_nuoc_ngoai; $totals['a4'] += $item['a4'];

            $bar[] = $item;
        }

        $data = [
            // Dữ liệu Pie Chart Cũ
            'employed' => ['pie' => [['category' => 'Có việc làm', 'value' => $totals['employed']], ['category' => 'Chưa có việc làm', 'value' => $totals['unemployed']]]],
            'location' => ['pie' => [['category' => 'Trong nước', 'value' => $totals['domestic']], ['category' => 'Nước ngoài', 'value' => $totals['foreign']]]],
            'field' => ['pie' => [['category' => 'Đúng ngành/LQ', 'value' => $totals['related']], ['category' => 'Trái ngành', 'value' => $totals['unrelated']]]],

            // Dữ liệu Pie Chart Mới (5 Loại)
            'status_detail' => [
                'pie' => [
                    ['category' => 'Đúng ngành', 'value' => $totals['s1']],
                    ['category' => 'Liên quan', 'value' => $totals['s2']],
                    ['category' => 'Trái ngành', 'value' => $totals['s3']],
                    ['category' => 'Tiếp tục học', 'value' => $totals['s4']],
                    ['category' => 'Chưa có việc', 'value' => $totals['s5']],
                ]
            ],
            // Dữ liệu Pie Chart Mới (4 Loại)
            'area_detail' => [
                'pie' => [
                    ['category' => 'Nhà nước', 'value' => $totals['a1']],
                    ['category' => 'Tư nhân', 'value' => $totals['a2']],
                    ['category' => 'Tự tạo việc', 'value' => $totals['a3']],
                    ['category' => 'Nước ngoài', 'value' => $totals['a4']],
                ]
            ],

            // Dữ liệu Bar Chart
            'bar' => $bar,
        ];

        return response()->json($data);
    }
}