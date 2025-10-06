<?php

namespace App\Http\Controllers;

use App\Exports\ReportExport;
use App\Models\EmploymentSurveyResponse;
use App\Models\GraduationStudent;
use App\Models\Student;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\StudentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct(private StudentService $studentService) {}

    public function index(Request $request)
    {
        $facultyId = $this->studentService->getFacultyId();


        // 2. Lấy access token từ cache hoặc gọi mới
        $token = cache()->remember('token_client1', 300, fn() => $this->studentService->post('/oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('auth.student.client_id'),
            'client_secret' => config('auth.student.client_secret'),
        ]));

        $accessToken = Arr::get($token, 'access_token');

        $graduationList = cache()->remember(
            "graduation_list_faculty_$facultyId",
            300,
            fn() => $this->studentService->get("/api/v1/external/graduation-ceremonies/faculty/$facultyId", [
                'access_token' => $accessToken
            ])
        );

        $graduations = collect($graduationList['data'] ?? []);
        $selectedGraduationId = $request->input('graduation_id') ?? optional($graduations->first())['id'];

        $industryList = $this->studentService->get("/api/v1/external/training-industries/faculty/$facultyId", [
            'access_token' => $accessToken
        ]);

        $industries = collect($industryList['data'] ?? [])->map(fn($i) => (object)$i);

        $surveyResponses = DB::table('employment_survey_responses_v2')->get();

        $report1 = $industries->map(function ($industry) use ($surveyResponses) {
            $responses = $surveyResponses->filter(fn($r) => ($r->training_industry_id ?? null) == $industry->id);
            $total = $responses->count();
            $female = $responses->filter(fn($r) => strtolower($r->gender ?? '') === 'female')->count();
            $hasJob = $responses->filter(fn($r) => $r->employment_status == 1)->count();
            $stillStudy = $responses->filter(fn($r) => $r->employment_status == 2)->count();
            $noJob = $responses->filter(fn($r) => $r->employment_status == 3)->count();
            $viecDungNganh = $responses->filter(fn($r) => ($r->trained_field ?? null) == 1)->count();
            $viecLienQuan = $responses->filter(fn($r) => ($r->trained_field ?? null) == 2)->count();
            $viecKhongLienQuan = $responses->filter(fn($r) => ($r->trained_field ?? null) == 3)->count();
            $lamNN = $responses->filter(fn($r) => $r->work_area == '1')->count();
            $lamTuNhan = $responses->filter(fn($r) => $r->work_area == '2')->count();
            $tuTaoViecLam = $responses->filter(fn($r) => $r->work_area == '3')->count();
            $lamViecNNg = $responses->filter(fn($r) => $r->work_area == '4')->count();

            return (object)[
                'training_industry_id' => $industry->code, 'ten_nganh' => $industry->name, 'sv_tot_nghiep' => $total,
                'sv_nu_tot_nghiep' => $female, 'tong_phan_hoi' => $total, 'nu_phan_hoi' => $female, 'co_viec_lam' => $hasJob,
                'viec_lam_dung_nganh' => $viecDungNganh, 'viec_lam_lien_quan' => $viecLienQuan, 'viec_lam_khong_lien_quan' => $viecKhongLienQuan,
                'tiep_tuc_hoc' => $stillStudy, 'chua_co_viec' => $noJob, 'ty_le_co_viec_phan_hoi' => $total > 0 ? round($hasJob / $total * 100, 2) : 0,
                'ty_le_co_viec_tot_nghiep' => $total > 0 ? round($hasJob / $total * 100, 2) : 0, 'lam_viec_nha_nuoc' => $lamNN,
                'lam_viec_tu_nhan' => $lamTuNhan, 'tu_tao_viec_lam' => $tuTaoViecLam, 'yeu_to_nuoc_ngoai' => $lamViecNNg,
            ];
        });

        $surveyMethods = ['Online', 'Điện thoại', 'Email'];
        $page = request('page', 1);
        $perPage = 10;
        $studentsAll = $surveyResponses->map(function ($r, $index) use ($industries, $surveyMethods) { /* ... */ });
        $students = new LengthAwarePaginator($studentsAll->slice(($page - 1) * $perPage, $perPage)->values(), $studentsAll->count(), $perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);
        $report3 = new LengthAwarePaginator($studentsAll->slice(($page - 1) * $perPage, $perPage)->values(), $studentsAll->count(), $perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);

        $studentTab2 = []; $r1 = []; $survey = null; $schoolYear = null; $r1_trained_field = null; $r1_work_area = null; $r2 = collect();
        if (request('survey_id')) {
            $survey = Survey::where('id', request('survey_id'))->first();
            if (empty($survey)) { abort(404); }
            $allDotTotNghiep = $survey->graduations()->get();
            $ids = $survey->graduations()->pluck('id')->toArray();
            $studentIds = GraduationStudent::whereIn('graduation_id', $ids)->pluck('student_id')->toArray();
            $studentTab2 = Student::query()->whereIn('id', $studentIds)->get();
            $schoolYear = !empty($allDotTotNghiep[0]->school_year) ? $allDotTotNghiep[0]->school_year : '';
            $r2 = EmploymentSurveyResponse::query()->where('survey_period_id', request('survey_id'))->get();
            $r1['total_student'] = count($studentTab2);
            $r1['total_nu'] = $studentTab2->where('gender','female')->count();
            $r1['total_res'] = count($r2);
            $r1['total_res_nu'] = $r2->where('gender','female')->count();
            $r1_trained_field = EmploymentSurveyResponse::query()->selectRaw("SUM(CASE WHEN trained_field = 1 THEN 1 ELSE 0 END) AS dung_nganh, SUM(CASE WHEN trained_field = 2 THEN 1 ELSE 0 END) AS lien_quan, SUM(CASE WHEN trained_field = 3 THEN 1 ELSE 0 END) AS khong_lien_quan")->where('survey_period_id', request('survey_id'))->first();
            $r1_work_area = EmploymentSurveyResponse::query()->selectRaw("SUM(CASE WHEN work_area = '1' THEN 1 ELSE 0 END) AS nha_nuoc, SUM(CASE WHEN work_area = '2' THEN 1 ELSE 0 END) AS tu_nhan, SUM(CASE WHEN work_area = '3' THEN 1 ELSE 0 END) AS tu_tao, SUM(CASE WHEN work_area = '4' THEN 1 ELSE 0 END) AS nuoc_ngoai")->where('survey_period_id', request('survey_id'))->first();
        }

        return view('admin.pages.admin.report', [
            'report1' => $report1, 'students' => $students, 'report3' => $report3,
            'graduationList' => $graduations, 'selectedGraduationId' => $selectedGraduationId,
            'survey' => $survey, 'schoolYear' => $schoolYear,
            'r1_trained_field' => $r1_trained_field, 'r1_work_area' => $r1_work_area,
            'studentTab2' => $studentTab2, 'r2' => $r2, 'r1' => $r1,
        ]);
    }

    public function export(Request $request)
    {
        $facultyId = $this->studentService->getFacultyId();
        $token = cache()->remember('token_client1', 300, fn() => $this->studentService->post('/oauth/token', ['grant_type' => 'client_credentials', 'client_id' => config('auth.student.client_id'), 'client_secret' => config('auth.student.client_secret')]));
        $accessToken = Arr::get($token, 'access_token');
        $industryList = $this->studentService->get("/api/v1/external/training-industries/faculty/$facultyId", ['access_token' => $accessToken]);
        $industries = collect($industryList['data'] ?? [])->map(fn($i) => (object)$i);
        $surveyResponses = DB::table('employment_survey_responses_v2')->get();

        $report1 = $industries->map(function ($industry) use ($surveyResponses) {
            $responses = $surveyResponses->filter(fn($r) => ($r->training_industry_id ?? null) == $industry->id);
            $total = $responses->count(); $female = $responses->filter(fn($r) => strtolower($r->gender ?? '') === 'female')->count();
            $hasJob = $responses->filter(fn($r) => $r->employment_status == 1)->count(); $stillStudy = $responses->filter(fn($r) => $r->employment_status == 2)->count();
            $noJob = $responses->filter(fn($r) => $r->employment_status == 3)->count(); $viecDungNganh = $responses->filter(fn($r) => ($r->trained_field ?? null) == 1)->count();
            $viecLienQuan = $responses->filter(fn($r) => ($r->trained_field ?? null) == 2)->count(); $viecKhongLienQuan = $responses->filter(fn($r) => ($r->trained_field ?? null) == 3)->count();
            $lamNN = $responses->filter(fn($r) => $r->work_area == '1')->count(); $lamTuNhan = $responses->filter(fn($r) => $r->work_area == '2')->count();
            $tuTaoViecLam = $responses->filter(fn($r) => $r->work_area == '3')->count(); $lamViecNNg = $responses->filter(fn($r) => $r->work_area == '4')->count();
            return (object)[
                'training_industry_id' => $industry->code, 'ten_nganh' => $industry->name, 'sv_tot_nghiep' => $total,
                'sv_nu_tot_nghiep' => $female, 'tong_phan_hoi' => $total, 'nu_phan_hoi' => $female, 'co_viec_lam' => $hasJob,
                'viec_lam_dung_nganh' => $viecDungNganh, 'viec_lam_lien_quan' => $viecLienQuan, 'viec_lam_khong_lien_quan' => $viecKhongLienQuan,
                'tiep_tuc_hoc' => $stillStudy, 'chua_co_viec' => $noJob, 'ty_le_co_viec_phan_hoi' => $total > 0 ? round($hasJob / $total * 100, 2) : 0,
                'ty_le_co_viec_tot_nghiep' => $total > 0 ? round($hasJob / $total * 100, 2) : 0, 'lam_viec_nha_nuoc' => $lamNN,
                'lam_viec_tu_nhan' => $lamTuNhan, 'tu_tao_viec_lam' => $tuTaoViecLam, 'yeu_to_nuoc_ngoai' => $lamViecNNg,
            ];
        });

        $studentsAll = $surveyResponses->map(function ($r, $index) use ($industries) { /* ... */ });
        $students = $studentsAll;
        $report3 = $studentsAll;

        $studentTab2 = []; $r1 = []; $survey = null; $schoolYear = null; $r1_trained_field = null; $r1_work_area = null; $r2 = collect();
        if (request('survey_id')) {
            $survey = Survey::where('id', request('survey_id'))->first();
            if (empty($survey)) { abort(404); }
            $allDotTotNghiep = $survey->graduations()->get();
            $ids = $survey->graduations()->pluck('id')->toArray();
            $studentIds = GraduationStudent::whereIn('graduation_id', $ids)->pluck('student_id')->toArray();
            $studentTab2 = Student::query()->whereIn('id', $studentIds)->get();
            $schoolYear = !empty($allDotTotNghiep[0]->school_year) ? $allDotTotNghiep[0]->school_year : '';
            $r2 = EmploymentSurveyResponse::query()->where('survey_period_id', request('survey_id'))->get();

            $r1['total_student'] = count($studentTab2);
            $r1['total_nu'] = $studentTab2->where('gender','female')->count();
            $r1['total_res'] = count($r2);
            $r1['total_res_nu'] = $r2->where('gender','female')->count();

            $r1_trained_field = EmploymentSurveyResponse::query()->selectRaw("SUM(CASE WHEN trained_field = 1 THEN 1 ELSE 0 END) AS dung_nganh, SUM(CASE WHEN trained_field = 2 THEN 1 ELSE 0 END) AS lien_quan, SUM(CASE WHEN trained_field = 3 THEN 1 ELSE 0 END) AS khong_lien_quan")->where('survey_period_id', request('survey_id'))->first();
            $r1_work_area = EmploymentSurveyResponse::query()->selectRaw("SUM(CASE WHEN work_area = '1' THEN 1 ELSE 0 END) AS nha_nuoc, SUM(CASE WHEN work_area = '2' THEN 1 ELSE 0 END) AS tu_nhan, SUM(CASE WHEN work_area = '3' THEN 1 ELSE 0 END) AS tu_tao, SUM(CASE WHEN work_area = '4' THEN 1 ELSE 0 END) AS nuoc_ngoai")->where('survey_period_id', request('survey_id'))->first();
        }

        $type = $request->input('type', 'report1');

        // **Sửa lỗi tại đây: Truyền 10 tham số riêng lẻ, bao gồm cả $type**
        return Excel::download(new ReportExport(
            $report1, $students, $report3, $schoolYear, $r1, $r1_trained_field,
            $r1_work_area, $r2, $studentTab2, $type
        ), 'bao-cao-'. $type .'.xlsx');
    }
}

