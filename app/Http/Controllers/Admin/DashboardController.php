<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
public function index(): View
{
    $lastResponse = DB::table('employment_survey_responses_v2')
        ->orderBy('survey_period_id', 'desc')
        ->first();

    $targetId = $lastResponse ? $lastResponse->survey_period_id : DB::table('survey')->max('id');

    $roundInfo = DB::table('survey')->where('id', $targetId)->first();

    $totalGraduates = 0;
    $totalResponses = 0;
    $kpi1 = 0; $kpi2 = 0; $kpi3 = 0; $kpi4 = 0;
    $roundName = 'Chưa có dữ liệu';

    if ($roundInfo) {
        $roundId = $roundInfo->id;
        $roundName = $roundInfo->title;
        $totalGraduates = (int) ($roundInfo->total_graduations ?? 0);

        $totalResponses = DB::table('employment_survey_responses_v2')
            ->where('survey_period_id', $roundId)
            ->count();

        $employedCount = DB::table('employment_survey_responses_v2')
            ->where('survey_period_id', $roundId)
            ->where('employment_status', 1)
            ->count();

        $relevantCount = DB::table('employment_survey_responses_v2')
            ->where('survey_period_id', $roundId)
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('employment_status', 1)
                      ->whereIn('trained_field', [1, 2]);
                })
                ->orWhere('employment_status', 3);
            })
            ->count();

        if ($totalGraduates > 0) {
            $kpi1 = round(($totalResponses / $totalGraduates) * 100, 2);
            $kpi3 = round(($employedCount / $totalGraduates) * 100, 2);
            $kpi4 = round(($relevantCount / $totalGraduates) * 100, 2);
        }

        if ($totalResponses > 0) {
            $kpi2 = round(($employedCount / $totalResponses) * 100, 2);
        }
    }

    return view('admin.pages.admin.dashboard', [
        'totalResponses'       => $totalResponses,
        'totalGraduates'       => $totalGraduates,
        'kpi1_ResponseRate'    => $kpi1,
        'kpi2_EmployedPerRes'  => $kpi2,
        'kpi3_EmployedPerGrad' => $kpi3,
        'kpi4_RelevantPerGrad' => $kpi4,
        'currentRoundName'     => $roundName
    ]);
}

    public function getChartData(): JsonResponse
    {
        $rows = DB::table('employment_survey_responses_v2 as esr')
            ->leftJoin('survey_periods as sp', 'esr.survey_period_id', '=', 'sp.id')
            ->select(
                'esr.survey_period_id',
                'sp.title as period_name',
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 THEN 1 ELSE 0 END) as employed_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status NOT IN (1, 3) THEN 1 ELSE 0 END) as unemployed_count'),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area IN ('1','2','3') THEN 1 ELSE 0 END) as domestic_count"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '4' THEN 1 ELSE 0 END) as foreign_count"),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field IN (1,2) THEN 1 ELSE 0 END) as related_field_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 3 THEN 1 ELSE 0 END) as unrelated_field_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 1 THEN 1 ELSE 0 END) as s1_dung_nganh'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 2 THEN 1 ELSE 0 END) as s2_lien_quan'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 3 THEN 1 ELSE 0 END) as s3_trai_nganh'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 3 THEN 1 ELSE 0 END) as s4_hoc_tiep'),
                DB::raw('SUM(CASE WHEN esr.employment_status NOT IN (1, 3) THEN 1 ELSE 0 END) as s5_chua_co_viec'),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '1' THEN 1 ELSE 0 END) as a1_nha_nuoc"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '2' THEN 1 ELSE 0 END) as a2_tu_nhan"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '3' THEN 1 ELSE 0 END) as a3_tu_tao"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '4' THEN 1 ELSE 0 END) as a4_nuoc_ngoai")
            )
            ->groupBy('esr.survey_period_id', 'sp.title')
            ->orderBy('esr.survey_period_id')
            ->get();

        // Prepare bar data
        $barData = [];
        foreach ($rows as $r) {
            $termName = $r->period_name ?? ('Đợt ' . $r->survey_period_id);
            if (preg_match('/(20\d{2})/', $termName, $matches)) {
                $termName = 'Năm ' . $matches[1];
            }

            $barData[] = [
                'term' => $termName,
                'employed' => (int) $r->employed_count,
                'unemployed' => (int) $r->unemployed_count,
                'domestic' => (int) $r->domestic_count,
                'foreign' => (int) $r->foreign_count,
                'related' => (int) $r->related_field_count,
                'unrelated' => (int) $r->unrelated_field_count,
                's1' => (int) $r->s1_dung_nganh,
                's2' => (int) $r->s2_lien_quan,
                's3' => (int) $r->s3_trai_nganh,
                's4' => (int) $r->s4_hoc_tiep,
                's5' => (int) $r->s5_chua_co_viec,
                'a1' => (int) $r->a1_nha_nuoc,
                'a2' => (int) $r->a2_tu_nhan,
                'a3' => (int) $r->a3_tu_tao,
                'a4' => (int) $r->a4_nuoc_ngoai,
            ];
        }

        // Get latest row for pie charts
        $latestRow = $rows->last();

        // Build response in the format JavaScript expects
        $response = [
            'bar' => $barData,
            'employed' => [
                'pie' => [
                    ['category' => 'Có việc làm', 'value' => $latestRow ? (int) $latestRow->employed_count : 0],
                    ['category' => 'Chưa có việc làm', 'value' => $latestRow ? (int) $latestRow->unemployed_count : 0],
                ],
                'bar' => $barData
            ],
            'location' => [
                'pie' => [
                    ['category' => 'Trong nước', 'value' => $latestRow ? (int) $latestRow->domestic_count : 0],
                    ['category' => 'Nước ngoài', 'value' => $latestRow ? (int) $latestRow->foreign_count : 0],
                ],
                'bar' => $barData
            ],
            'field' => [
                'pie' => [
                    ['category' => 'Đúng ngành/LQ', 'value' => $latestRow ? (int) $latestRow->related_field_count : 0],
                    ['category' => 'Trái ngành', 'value' => $latestRow ? (int) $latestRow->unrelated_field_count : 0],
                ],
                'bar' => $barData
            ],
            'status_detail' => [
                'pie' => [
                    ['category' => 'Đúng ngành', 'value' => $latestRow ? (int) $latestRow->s1_dung_nganh : 0],
                    ['category' => 'Liên quan', 'value' => $latestRow ? (int) $latestRow->s2_lien_quan : 0],
                    ['category' => 'Trái ngành', 'value' => $latestRow ? (int) $latestRow->s3_trai_nganh : 0],
                    ['category' => 'Tiếp tục học', 'value' => $latestRow ? (int) $latestRow->s4_hoc_tiep : 0],
                    ['category' => 'Chưa có việc', 'value' => $latestRow ? (int) $latestRow->s5_chua_co_viec : 0],
                ],
                'bar' => $barData
            ],
            'area_detail' => [
                'pie' => [
                    ['category' => 'Nhà nước', 'value' => $latestRow ? (int) $latestRow->a1_nha_nuoc : 0],
                    ['category' => 'Tư nhân', 'value' => $latestRow ? (int) $latestRow->a2_tu_nhan : 0],
                    ['category' => 'Tự tạo việc', 'value' => $latestRow ? (int) $latestRow->a3_tu_tao : 0],
                    ['category' => 'Nước ngoài', 'value' => $latestRow ? (int) $latestRow->a4_nuoc_ngoai : 0],
                ],
                'bar' => $barData
            ]
        ];

        return response()->json($response);
    }
}