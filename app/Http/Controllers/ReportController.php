<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\EmploymentSurveyResponse;
use App\Models\GraduationStudent;
use App\Models\Student;
use App\Models\Survey;
use App\Models\City;
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
         $studentTab2 = collect();
        $r1 = [];
        $survey = null;
        $schoolYear = null;
        $r1_trained_field = null;
        $r1_work_area = null;
        $r2 = collect();
        $alumniData = collect();

         if ($request->filled('survey_id')) {
            $survey = Survey::find($request->survey_id);

            if (!$survey) {
                abort(404, 'Không tìm thấy khảo sát');
            }

             $allDotTotNghiep = $survey->graduations()->get();
            $graduationIds = $survey->graduations()->pluck('id')->toArray();

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

             $studentTab2 = Student::whereIn('id', $studentIds)
                ->with('major:id,code,name')
                ->get()
                ->map(function ($student) {
                     $graduation = DB::table('graduation_student')
                        ->join('graduation', 'graduation_student.graduation_id', '=', 'graduation.id')
                        ->where('graduation_student.student_id', $student->id)
                        ->select('graduation.certification', 'graduation.certification_date', 'graduation.school_year')
                        ->first();

                    $student->graduation = $graduation;
                    $student->school_year = $graduation->school_year ?? '';
                    return $student;
                });

             $r2 = EmploymentSurveyResponse::where('survey_period_id', $request->survey_id)
                ->with(['major:id,code,name', 'city:id,code,name'])
                ->get();

             $studentCodes = $studentTab2->pluck('code')->toArray();

            $alumniData = DB::table('alumni_contact_surveys')
                ->whereIn('student_code', $studentCodes)
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($item) {
                    // Decode JSON nếu có
                    if (!empty($item->connection_group)) {
                        $item->connection_groups = @json_decode($item->connection_group, true) ?? [];
                    }
                    return $item;
                });

             $schoolYear = $allDotTotNghiep->first()->school_year ?? '';

             $r1['total_student'] = $studentTab2->count();
            $r1['total_nu'] = $studentTab2->where('gender', 'female')->count();
            $r1['total_res'] = $r2->count();
            $r1['total_res_nu'] = $r2->where('gender', 'female')->count();

             $r1['co_viec_lam'] = $r2->where('employment_status', 1)->count();
            $r1['tiep_tuc_hoc'] = $r2->where('employment_status', 2)->count();
            $r1['chua_co_viec'] = $r2->where('employment_status', 3)->count();
            $r1['khong_phan_hoi'] = $r2->where('employment_status', 4)->count();

             $r1_trained_field = EmploymentSurveyResponse::selectRaw("
                SUM(CASE WHEN trained_field = 1 AND employment_status = 1 THEN 1 ELSE 0 END) AS dung_nganh,
                SUM(CASE WHEN trained_field = 2 AND employment_status = 1 THEN 1 ELSE 0 END) AS lien_quan,
                SUM(CASE WHEN trained_field = 3 AND employment_status = 1 THEN 1 ELSE 0 END) AS khong_lien_quan
            ")
                ->where('survey_period_id', $request->survey_id)
                ->first();

             $r1_work_area = EmploymentSurveyResponse::selectRaw("
                SUM(CASE WHEN work_area = '1' AND employment_status = 1 THEN 1 ELSE 0 END) AS nha_nuoc,
                SUM(CASE WHEN work_area = '2' AND employment_status = 1 THEN 1 ELSE 0 END) AS tu_nhan,
                SUM(CASE WHEN work_area = '3' AND employment_status = 1 THEN 1 ELSE 0 END) AS tu_tao,
                SUM(CASE WHEN work_area = '4' AND employment_status = 1 THEN 1 ELSE 0 END) AS nuoc_ngoai
            ")
                ->where('survey_period_id', $request->survey_id)
                ->first();

            // Tính tỷ lệ có việc làm
            $totalCoViecLam = $r1_trained_field->dung_nganh + $r1_trained_field->lien_quan + $r1_trained_field->khong_lien_quan;
            $r1['ty_le_viec_lam_phan_hoi'] = $r1['total_res'] > 0
                ? round($totalCoViecLam / $r1['total_res'] * 100, 2)
                : 0;
            $r1['ty_le_viec_lam_tot_nghiep'] = $r1['total_student'] > 0
                ? round($totalCoViecLam / $r1['total_student'] * 100, 2)
                : 0;
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

         $type = $request->get('type', 'all');

         $allDotTotNghiep = $survey->graduations()->get();
        $graduationIds = $survey->graduations()->pluck('id')->toArray();
        $studentIds = GraduationStudent::whereIn('graduation_id', $graduationIds)
            ->pluck('student_id')
            ->toArray();

        if (empty($studentIds)) {
            return back()->with('error', 'Không có sinh viên nào trong đợt khảo sát này.');
        }

        $schoolYear = $allDotTotNghiep->first()->school_year ?? '';


        $studentTab2 = Student::whereIn('id', $studentIds)
            ->with('major:id,code,name')
            ->get()
            ->map(function ($student) {
                $graduation = DB::table('graduation_student')
                    ->join('graduation', 'graduation_student.graduation_id', '=', 'graduation.id')
                    ->where('graduation_student.student_id', $student->id)
                    ->select('graduation.certification', 'graduation.certification_date', 'graduation.school_year')
                    ->first();

                $student->graduation = $graduation;
                return $student;
            });

        $r2 = EmploymentSurveyResponse::where('survey_period_id', $request->survey_id)
            ->with(['major:id,code,name', 'city:id,code,name'])
            ->get();

         $r1 = [
            'total_student' => $studentTab2->count(),
            'total_nu' => $studentTab2->where('gender', 'female')->count(),
            'total_res' => $r2->count(),
            'total_res_nu' => $r2->where('gender', 'female')->count(),
            'co_viec_lam' => $r2->where('employment_status', 1)->count(),
            'tiep_tuc_hoc' => $r2->where('employment_status', 2)->count(),
            'chua_co_viec' => $r2->where('employment_status', 3)->count(),
        ];

        $r1_trained_field = EmploymentSurveyResponse::selectRaw("
            SUM(CASE WHEN trained_field = 1 AND employment_status = 1 THEN 1 ELSE 0 END) AS dung_nganh,
            SUM(CASE WHEN trained_field = 2 AND employment_status = 1 THEN 1 ELSE 0 END) AS lien_quan,
            SUM(CASE WHEN trained_field = 3 AND employment_status = 1 THEN 1 ELSE 0 END) AS khong_lien_quan
        ")
            ->where('survey_period_id', $request->survey_id)
            ->first();

        $r1_work_area = EmploymentSurveyResponse::selectRaw("
            SUM(CASE WHEN work_area = '1' AND employment_status = 1 THEN 1 ELSE 0 END) AS nha_nuoc,
            SUM(CASE WHEN work_area = '2' AND employment_status = 1 THEN 1 ELSE 0 END) AS tu_nhan,
            SUM(CASE WHEN work_area = '3' AND employment_status = 1 THEN 1 ELSE 0 END) AS tu_tao,
            SUM(CASE WHEN work_area = '4' AND employment_status = 1 THEN 1 ELSE 0 END) AS nuoc_ngoai
        ")
            ->where('survey_period_id', $request->survey_id)
            ->first();

         $studentCodes = $studentTab2->pluck('code')->toArray();
        $alumniData = DB::table('alumni_contact_surveys')
             ->orderBy('created_at', 'desc')
            ->get();

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
