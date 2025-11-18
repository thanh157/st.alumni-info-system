<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Graduation; // Đảm bảo đã import Model này
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /** Hiển thị dashboard với 4 KPI mới */
    public function index(): View
    {
<<<<<<< Updated upstream
        $totalResponses = (int) DB::table('employment_survey_responses_v2')->count();

        $totalEmployed  = (int) DB::table('employment_survey_responses_v2')
            ->where('employment_status', 1)
            ->count();

        $employmentRate = $totalResponses > 0
            ? (int) round(($totalEmployed / $totalResponses) * 100)
            : 0;

        $totalGraduations = (int) Graduation::count();
=======
        // 1. TỔNG SỐ SV TỐT NGHIỆP (Mẫu số chung)
        // Nếu chưa có model Graduation, bạn có thể dùng DB::table('graduations')->count();
        $totalGraduates = Graduation::count();

        // 2. TỔNG SỐ PHẢN HỒI
        $totalResponses = DB::table('employment_survey_responses_v2')->count();

        // 3. SỐ LƯỢNG CÓ VIỆC LÀM (Status = 1)
        $employedCount = DB::table('employment_survey_responses_v2')
            ->where('employment_status', 1)
            ->count();

        // 4. SỐ LƯỢNG VIỆC LÀM PHÙ HỢP
        // Công thức: (Có việc & (Đúng ngành [1] hoặc Liên quan [2])) HOẶC (Đi học tiếp [3])
        $relevantCount = DB::table('employment_survey_responses_v2')
            ->where(function ($query) {
                $query->where('employment_status', 1)
                    ->whereIn('trained_field', [1, 2]);
            })
            ->orWhere('employment_status', 3)
            ->count();

        // --- TÍNH TOÁN TỶ LỆ (%) ---

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
>>>>>>> Stashed changes

        $totalClasses = 15;
        return view('admin.pages.admin.dashboard', compact(
            'totalResponses',
<<<<<<< Updated upstream
            'employmentRate',
            'totalGraduations',
            'totalClasses'
=======
            'totalGraduates',
            'kpi1_ResponseRate',
            'kpi2_EmployedPerRes',
            'kpi3_EmployedPerGrad',
            'kpi4_RelevantPerGrad'
>>>>>>> Stashed changes
        ));
    }

    /** API trả dữ liệu cho biểu đồ (Đã thêm chi tiết) */
    public function getChartData(): JsonResponse
    {
        $rows = DB::table('employment_survey_responses_v2 as esr')
            ->leftJoin('survey_periods as sp', 'esr.survey_period_id', '=', 'sp.id')
            ->select(
                'esr.survey_period_id',
                'sp.title as period_name',

                // --- CŨ (Giữ nguyên logic cũ) ---
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 THEN 1 ELSE 0 END) as employed_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status != 1 AND esr.employment_status != 3 THEN 1 ELSE 0 END) as unemployed_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field IN (1,2) THEN 1 ELSE 0 END) as related_field_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 3 THEN 1 ELSE 0 END) as unrelated_field_count'),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area IN ('1','2','3') THEN 1 ELSE 0 END) as domestic_count"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '4' THEN 1 ELSE 0 END) as foreign_count"),

                // --- MỚI: CHI TIẾT 5 LOẠI TÌNH HÌNH VIỆC LÀM ---
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 1 THEN 1 ELSE 0 END) as s1_dung_nganh'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 2 THEN 1 ELSE 0 END) as s2_lien_quan'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 3 THEN 1 ELSE 0 END) as s3_trai_nganh'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 3 THEN 1 ELSE 0 END) as s4_hoc_tiep'),
                DB::raw('SUM(CASE WHEN esr.employment_status != 1 AND esr.employment_status != 3 THEN 1 ELSE 0 END) as s5_chua_co_viec'),

                // --- MỚI: CHI TIẾT 4 KHU VỰC ---
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '1' THEN 1 ELSE 0 END) as a1_nha_nuoc"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '2' THEN 1 ELSE 0 END) as a2_tu_nhan"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '3' THEN 1 ELSE 0 END) as a3_tu_tao"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '4' THEN 1 ELSE 0 END) as a4_nuoc_ngoai")
            )
            ->groupBy('esr.survey_period_id', 'sp.title')
            ->orderBy('esr.survey_period_id')
            ->get();

        // Khởi tạo mảng tổng
        $totals = [
            'employed'   => 0,
            'unemployed' => 0,
<<<<<<< Updated upstream
            'related'    => 0,
            'unrelated'  => 0,
            'domestic'   => 0,
            'foreign'    => 0,
=======
            'related' => 0,
            'unrelated' => 0,
            'domestic' => 0,
            'foreign' => 0,
            // Mới
            's1' => 0,
            's2' => 0,
            's3' => 0,
            's4' => 0,
            's5' => 0,
            'a1' => 0,
            'a2' => 0,
            'a3' => 0,
            'a4' => 0,
>>>>>>> Stashed changes
        ];

        $bar = [];

        foreach ($rows as $r) {
<<<<<<< Updated upstream
            // Cast an toàn
            $employed   = (int) ($r->employed_count ?? 0);
            $unemployed = (int) ($r->unemployed_count ?? 0);
            $related    = (int) ($r->related_field_count ?? 0);
            $unrelated  = (int) ($r->unrelated_field_count ?? 0);
            $domestic   = (int) ($r->domestic_count ?? 0);
            $foreign    = (int) ($r->foreign_count ?? 0);

            // Tổng
            $totals['employed']   += $employed;
            $totals['unemployed'] += $unemployed;
            $totals['related']    += $related;
            $totals['unrelated']  += $unrelated;
            $totals['domestic']   += $domestic;
            $totals['foreign']    += $foreign;

            // Dòng bar
            $bar[] = [
                'term'       => 'Đợt Khảo Sát ' . $r->survey_period_id,
                'employed'   => $employed,
                'unemployed' => $unemployed,
                'related'    => $related,
                'unrelated'  => $unrelated,
                'domestic'   => $domestic,
                'foreign'    => $foreign,
            ];
=======
            // Xử lý tên đợt cho đẹp (VD: Năm 2024)
            $termName = $r->period_name ?? ('Đợt ' . $r->survey_period_id);
            if (preg_match('/(20\d{2})/', $termName, $matches)) {
                $termName = 'Năm ' . $matches[1];
            }

            $item = ['term' => $termName];

            // Mapping dữ liệu vào item và cộng tổng
            $item['employed'] = (int) $r->employed_count;
            $totals['employed'] += $item['employed'];
            $item['unemployed'] = (int) $r->unemployed_count;
            $totals['unemployed'] += $item['unemployed'];

            $item['domestic'] = (int) $r->domestic_count;
            $totals['domestic'] += $item['domestic'];
            $item['foreign'] = (int) $r->foreign_count;
            $totals['foreign'] += $item['foreign'];

            $item['related'] = (int) $r->related_field_count;
            $totals['related'] += $item['related'];
            $item['unrelated'] = (int) $r->unrelated_field_count;
            $totals['unrelated'] += $item['unrelated'];

            // Data Mới
            $item['s1'] = (int) $r->s1_dung_nganh;
            $totals['s1'] += $item['s1'];
            $item['s2'] = (int) $r->s2_lien_quan;
            $totals['s2'] += $item['s2'];
            $item['s3'] = (int) $r->s3_trai_nganh;
            $totals['s3'] += $item['s3'];
            $item['s4'] = (int) $r->s4_hoc_tiep;
            $totals['s4'] += $item['s4'];
            $item['s5'] = (int) $r->s5_chua_co_viec;
            $totals['s5'] += $item['s5'];

            $item['a1'] = (int) $r->a1_nha_nuoc;
            $totals['a1'] += $item['a1'];
            $item['a2'] = (int) $r->a2_tu_nhan;
            $totals['a2'] += $item['a2'];
            $item['a3'] = (int) $r->a3_tu_tao;
            $totals['a3'] += $item['a3'];
            $item['a4'] = (int) $r->a4_nuoc_ngoai;
            $totals['a4'] += $item['a4'];

            $bar[] = $item;
>>>>>>> Stashed changes
        }

        $data = [
            // Dữ liệu cho Pie Chart
            'employed' => ['pie' => [['category' => 'Có việc làm', 'value' => $totals['employed']], ['category' => 'Chưa có việc làm', 'value' => $totals['unemployed']]]],
            'location' => ['pie' => [['category' => 'Trong nước', 'value' => $totals['domestic']], ['category' => 'Nước ngoài', 'value' => $totals['foreign']]]],
            'field' => ['pie' => [['category' => 'Đúng ngành/LQ', 'value' => $totals['related']], ['category' => 'Trái ngành', 'value' => $totals['unrelated']]]],

            // Dữ liệu Pie Chart Mới (Chi tiết)
            'status_detail' => [
                'pie' => [
<<<<<<< Updated upstream
                    ['category' => 'Có việc làm',       'value' => $totals['employed']],
                    ['category' => 'Chưa có việc làm',  'value' => $totals['unemployed']],
                ],
=======
                    ['category' => 'Đúng ngành', 'value' => $totals['s1']],
                    ['category' => 'Liên quan', 'value' => $totals['s2']],
                    ['category' => 'Trái ngành', 'value' => $totals['s3']],
                    ['category' => 'Tiếp tục học', 'value' => $totals['s4']],
                    ['category' => 'Chưa có việc', 'value' => $totals['s5']],
                ]
>>>>>>> Stashed changes
            ],
            'area_detail' => [
                'pie' => [
                    ['category' => 'Nhà nước', 'value' => $totals['a1']],
                    ['category' => 'Tư nhân', 'value' => $totals['a2']],
                    ['category' => 'Tự tạo việc', 'value' => $totals['a3']],
                    ['category' => 'Nước ngoài', 'value' => $totals['a4']],
                ]
            ],

            // Dữ liệu Bar Chart (Chung cho tất cả)
            'bar' => $bar,
        ];

        return response()->json($data);
    }
}