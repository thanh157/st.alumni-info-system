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
        // 1. LẤY ĐỢT KHẢO SÁT MỚI NHẤT
        $latestRound = DB::table('survey_periods')
            ->orderBy('id', 'desc')
            ->first();

        $totalGraduates = 0;
        $totalResponses = 0;
        $kpi1 = 0; $kpi2 = 0; $kpi3 = 0; $kpi4 = 0;
        $roundName = '';

        if ($latestRound) {
            $roundId = $latestRound->id;
            $roundName = $latestRound->title;
            
            // Lấy tổng SV tốt nghiệp từ cột 'total' bảng survey_periods
            $totalGraduates = (int) $latestRound->total;

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

            $kpi1 = $totalGraduates > 0 ? round(($totalResponses / $totalGraduates) * 100, 2) : 0;
            $kpi2 = $totalResponses > 0 ? round(($employedCount / $totalResponses) * 100, 2) : 0;
            $kpi3 = $totalGraduates > 0 ? round(($employedCount / $totalGraduates) * 100, 2) : 0;
            $kpi4 = $totalGraduates > 0 ? round(($relevantCount / $totalGraduates) * 100, 2) : 0;
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
        // Query dữ liệu cho BAR CHART (Lấy hết các đợt)
        $rows = DB::table('employment_survey_responses_v2 as esr')
            ->leftJoin('survey_periods as sp', 'esr.survey_period_id', '=', 'sp.id')
            ->select(
                'esr.survey_period_id',
                'sp.title as period_name',
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 THEN 1 ELSE 0 END) as employed_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status != 1 AND esr.employment_status != 3 THEN 1 ELSE 0 END) as unemployed_count'),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area IN ('1','2','3') THEN 1 ELSE 0 END) as domestic_count"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '4' THEN 1 ELSE 0 END) as foreign_count"),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field IN (1,2) THEN 1 ELSE 0 END) as related_field_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 3 THEN 1 ELSE 0 END) as unrelated_field_count'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 1 THEN 1 ELSE 0 END) as s1_dung_nganh'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 2 THEN 1 ELSE 0 END) as s2_lien_quan'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 1 AND esr.trained_field = 3 THEN 1 ELSE 0 END) as s3_trai_nganh'),
                DB::raw('SUM(CASE WHEN esr.employment_status = 3 THEN 1 ELSE 0 END) as s4_hoc_tiep'),
                DB::raw('SUM(CASE WHEN esr.employment_status != 1 AND esr.employment_status != 3 THEN 1 ELSE 0 END) as s5_chua_co_viec'),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '1' THEN 1 ELSE 0 END) as a1_nha_nuoc"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '2' THEN 1 ELSE 0 END) as a2_tu_nhan"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '3' THEN 1 ELSE 0 END) as a3_tu_tao"),
                DB::raw("SUM(CASE WHEN esr.employment_status = 1 AND esr.work_area = '4' THEN 1 ELSE 0 END) as a4_nuoc_ngoai")
            )
            ->groupBy('esr.survey_period_id', 'sp.title')
            ->orderBy('esr.survey_period_id') // Sắp xếp để đợt mới nhất nằm cuối
            ->get();

        $bar = [];
        
        // Mapping tên cột
        $map = [
            'employed' => 'employed_count', 'unemployed' => 'unemployed_count',
            'domestic' => 'domestic_count', 'foreign' => 'foreign_count',
            'related' => 'related_field_count', 'unrelated' => 'unrelated_field_count',
            's1' => 's1_dung_nganh', 's2' => 's2_lien_quan', 's3' => 's3_trai_nganh', 's4' => 's4_hoc_tiep', 's5' => 's5_chua_co_viec',
            'a1' => 'a1_nha_nuoc', 'a2' => 'a2_tu_nhan', 'a3' => 'a3_tu_tao', 'a4' => 'a4_nuoc_ngoai'
        ];

        // Xử lý dữ liệu Bar Chart
        foreach ($rows as $r) {
            $termName = $r->period_name ?? ('Đợt ' . $r->survey_period_id);
            if (preg_match('/(20\d{2})/', $termName, $matches)) {
                $termName = 'Năm ' . $matches[1];
            }

            $item = ['term' => $termName];
            foreach($map as $key => $col) {
                $item[$key] = (int)$r->$col;
            }
            $bar[] = $item;
        }

        // Xử lý dữ liệu Pie Chart: LẤY CỦA ĐỢT MỚI NHẤT (Row cuối cùng)
        $latestRow = $rows->last(); 
        $pieData = [];
        $latestName = "Chưa có dữ liệu";

        if ($latestRow) {
            foreach($map as $key => $col) {
                $pieData[$key] = (int)$latestRow->$col;
            }
            // Lấy tên đợt mới nhất
            $latestName = $latestRow->period_name ?? ('Đợt ' . $latestRow->survey_period_id);
            if (preg_match('/(20\d{2})/', $latestName, $matches)) {
                $latestName = 'Năm ' . $matches[1];
            }
        } else {
            // Default 0
            foreach($map as $key => $col) $pieData[$key] = 0;
        }

        return response()->json([
            'pie_data' => $pieData,     // Dữ liệu của đợt mới nhất
            'latest_name' => $latestName, // Tên đợt mới nhất
            'bar' => $bar               // Dữ liệu lịch sử các đợt
        ]);
    }
}