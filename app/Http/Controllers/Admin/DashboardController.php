<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Graduation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /** Hiển thị dashboard */
    public function index(): View
    {
        $totalResponses = (int) DB::table('employment_survey_responses_v2')->count();

        $totalEmployed  = (int) DB::table('employment_survey_responses_v2')
            ->where('employment_status', 1)
            ->count();

        $employmentRate = $totalResponses > 0
            ? (int) round(($totalEmployed / $totalResponses) * 100)
            : 0;

        $totalGraduations = (int) Graduation::count();

        $totalClasses = 15;

        return view('admin.pages.admin.dashboard', compact(
            'totalResponses',
            'employmentRate',
            'totalGraduations',
            'totalClasses'
        ));
    }

    public function getChartData(): JsonResponse
    {
        // Truy vấn theo đợt tốt nghiệp graduation -> student(lấy mã sv)-> response->đếm+gom nhóm+ sắp xếp
        $rows = DB::table('graduation as g')
            ->join('graduation_student as gs', 'g.id', '=', 'gs.graduation_id')
            ->join('student as s', 'gs.student_id', '=', 's.id')
            ->leftJoin('employment_survey_responses_v2 as esr', 's.code', '=', 'esr.code_student')
            ->select(
                'g.id',
                'g.certification_date',
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 THEN 1 ELSE 0 END) as employed_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status != 1 OR esr.employment_status IS NULL THEN 1 ELSE 0 END) as unemployed_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field IN (1,2) THEN 1 ELSE 0 END) as related_field_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 3 THEN 1 ELSE 0 END) as unrelated_field_count'),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area IN ('1','2','3') THEN 1 ELSE 0 END) as domestic_count"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '4' THEN 1 ELSE 0 END) as foreign_count")
            )
            ->groupBy('g.id', 'g.certification_date')
            ->orderBy('g.certification_date')
            ->get();

        $totals = [
            'employed'   => 0,
            'unemployed' => 0,
            'related'    => 0,
            'unrelated'  => 0,
            'domestic'   => 0,
            'foreign'    => 0,
        ];

        $bar = [];

        foreach ($rows as $r) {
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
                'term'       => 'Đợt ' . Carbon::parse($r->certification_date)->format('m/Y'),
                'employed'   => $employed,
                'unemployed' => $unemployed,
                'related'    => $related,
                'unrelated'  => $unrelated,
                'domestic'   => $domestic,
                'foreign'    => $foreign,
            ];
        }

        $data = [
            // 3 bộ pie (theo chế độ)
            'employed' => [
                'pie' => [
                    ['category' => 'Có việc làm',       'value' => $totals['employed']],
                    ['category' => 'Chưa có việc làm',  'value' => $totals['unemployed']],
                ],
            ],
            'location' => [
                'pie' => [
                    ['category' => 'Trong nước', 'value' => $totals['domestic']],
                    ['category' => 'Nước ngoài', 'value' => $totals['foreign']],
                ],
            ],
            'field' => [
                'pie' => [
                    ['category' => 'Đúng ngành', 'value' => $totals['related']],
                    ['category' => 'Trái ngành', 'value' => $totals['unrelated']],
                ],
            ],
            // dữ liệu bar theo từng đợt (dùng chung)
            'bar' => $bar,
        ];

        return response()->json($data);
    }
}
