<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\DotTotnghiep;
use App\Models\DotTotNghiepStudent;
use App\Models\EmploymentSurveyResponse;
use App\Models\GraduationStudent;
use App\Models\GraduationSurvey;
use App\Models\Major;
use App\Models\Student;
use App\Models\Survey;
use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use App\Services\StudentService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use App\Models\Graduation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;
use Throwable;
use ZipArchive;

class SurveyResultController extends Controller
{
    public function __construct(private StudentService $studentService) {}
    public function index(Request $request, $surveyId)
    {
        try {
            $user = auth()->user();

            // Lấy access token nếu chưa có
            if (empty($user->st_students_token)) {
                $tokenData = $this->studentService->getAccessTokenVerify();
                if (!$tokenData || empty($tokenData['token'])) {
                    throw new \Exception('Không lấy được access token của sinh viên.');
                }
            }

            // Gọi API lấy danh sách sinh viên theo đợt TN
            $apiUrl = config('auth.student.ip') . "/api/v1/external/graduation-ceremonies/survey-graduations";
            $graduationIds = GraduationSurvey::where('survey_id', $surveyId)
                ->pluck('graduation_id')
                ->toArray();

            $response = Http::withToken($user->st_students_token)
                ->timeout(10)
                ->get($apiUrl, [
                    'ids' => $graduationIds
                ])
                ->json();

            if (!isset($response['data'])) {
                throw new \Exception('API trả về dữ liệu không hợp lệ.');
            }

            $graduations = $response['data']['graduations'];
            $schoolYear = $graduations[0]['school_year'] ?? '';

            $query = EmploymentSurveyResponse::query()
                ->with(['student'])
                ->where('survey_period_id', $surveyId);


            // Tìm kiếm tổng hợp (search)
            $searchQuery = $request->input('search');

            if ($searchQuery) {
                $query->where(function ($q) use ($searchQuery) {
                    $q->where('code_student', 'like', '%' . $searchQuery . '%')
                        ->orWhere('full_name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('email', 'like', '%' . $searchQuery . '%')
                        ->orWhere('phone_number', 'like', '%' . $searchQuery . '%')
                        ->orWhere('identification_card_number', 'like', '%' . $searchQuery . '%');
                });
            }

            //      // ===== THÊM MỚI: Lọc theo trạng thái việc làm =====
            if ($request->filled('filter')) {
                $filter = $request->filter;

                // Employment Status (es_)
                if (str_starts_with($filter, 'es_')) {
                    $statusList = str_replace('es_', '', $filter); // "1_2"
                    $statusList = explode('_', $statusList);        // [1, 2]
                    $query->whereIn('employment_status', $statusList);
                }

                // Trained Field (tf_)
                elseif (str_starts_with($filter, 'tf_')) {
                    $trainedField = str_replace('tf_', '', $filter); // "1" hoặc "2" hoặc "3"
                    $query->where('trained_field', $trainedField);
                }
            }

            // // Lọc theo mã sinh viên
            // if ($request->filled('student_code')) {
            //     $query->whereHas('student', function ($q) use ($request) {
            //         $q->where('student_code', 'like', '%' . $request->student_code . '%');
            //     });
            // }

            // // Lọc theo tên sinh viên
            // if ($request->filled('student_name')) {
            //     $query->whereHas('student', function ($q) use ($request) {
            //         $q->where('full_name', 'like', '%' . $request->student_name . '%');
            //     });
            // }

            // // Lọc theo đợt tốt nghiệp
            // if ($request->filled('graduation_id')) {
            //     $query->where('graduation_id', $request->graduation_id);
            // }

            $data = $query->orderBy('id', 'desc')->paginate(15);

            $survey = Survey::where('id', $surveyId)->first();

            $coViec = EmploymentSurveyResponse::where('survey_period_id', $survey->id)
                ->whereIn('employment_status', [1, 2])
                ->count();

            $dungNganh = EmploymentSurveyResponse::where('survey_period_id', $survey->id)
                ->whereIn('employment_status', [1, 2])
                ->where('trained_field', 1)
                ->count();

            $lienQuan = EmploymentSurveyResponse::where('survey_period_id', $survey->id)
                ->whereIn('employment_status', [1, 2])
                ->where('trained_field', 2)
                ->count();

            return view('admin.pages.admin.survey.result', [
                'data' => $data,
                'schoolYear' => $schoolYear,
                'allDotTotNghiep' => $graduations,
                'survey' => $survey,
                'coViec' => $coViec,
                'dungNganh' => $dungNganh,
                'lienQuan' => $lienQuan,
                'request' => $request, // Gửi lại input để giữ giá trị trong form
            ]);
        } catch (Throwable $th) {
            Log::error(" error: " . $th->getMessage());
            return [];
        }
    }

    public function show($id)
    {
        $response = EmploymentSurveyResponse::query()
            ->with(['student', 'survey'])
            ->where('id', $id)->first();
        if (empty($response)) {
            abort(404);
        }

        $major = Major::query()->pluck('name', 'id')->toArray();

        $viewData = [
            'response' => $response,
            'student' => $response->student,
            'survey' => $response->survey,
            'major' => $major,
        ];
        return view('admin.pages.admin.survey.result_detail', $viewData);
    }

    public function exportPdf($survey_id)
    {
        $response = EmploymentSurveyResponse::query()
            ->with(['student', 'survey'])
            ->where('id', $survey_id)->first();
        if (empty($response)) {
            abort(404);
        }

        $major = Major::query()->pluck('name', 'id')->toArray();

        $viewData = [
            'response' => $response,
            'student' => $response->student,
            'survey' => $response->survey,
            'major' => $major,
        ];

        $pdf = Pdf::loadView('admin.pages.admin.survey.result_detail_2', $viewData)
            ->setOptions([
                'defaultFont' => 'DejaVu Sans',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultPaperSize' => 'a4',
            ]);


        return $pdf->download($response->code_student . '_' . $response->full_name . '.pdf');
    }
    public function downloadAllPdfs($survey_id)
    {

        $survey = Survey::findOrFail($survey_id);
        $responses = EmploymentSurveyResponse::with('student')

            ->where('survey_period_id', $survey_id)->get();
        set_time_limit(300);

        ini_set('memory_limit', '512M');

        if ($responses->isEmpty()) {
            return back()->with('error', 'Khảo sát này chưa có thông tin !');
        }

        $zipFileName = 'Phieu-khao-sat-viec-lam-SVTN.zip';
        $zipFilePath = storage_path('app/temp/' . $zipFileName);

        if (!File::exists(storage_path('app/temp'))) {
            File::makeDirectory(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            return back()->with('error', 'Không thể tạo file zip.');
        }

        foreach ($responses as $response) {
            $pdf = Pdf::loadView('admin.pages.admin.survey.result_detail_2', [
                'response' => $response,
                'survey' => $survey
            ]);

            $pdfContent = $pdf->output();

            $pdfFileName = $response->code_student . '_' . $response->full_name . '.pdf';

            $zip->addFromString($pdfFileName, $pdfContent);
        }

        $zip->close();
        $cookie = cookie('download_token', 'completed', 1, '/');
        return response()
            ->download($zipFilePath, $zipFileName)
            ->deleteFileAfterSend(true)
        ;
    }

    // public function downloadAllPdfs($survey_id)
    // {
    //     $survey = Survey::findOrFail($survey_id);

    //     $responses = EmploymentSurveyResponse::with('student')
    //         ->where('survey_period_id', $survey_id)
    //         ->get();

    //     set_time_limit(300);
    //     ini_set('memory_limit', '512M');

    //     if ($responses->isEmpty()) {
    //         return back()->with('error', 'Khảo sát này chưa có thông tin !');
    //     }

    //     $major = Major::query()->pluck('name', 'id')->toArray();

    //     $yearString = $responses->first()->nam_tot_nghiep ?? 'N/A';

    //     $zipFileName = 'Phieu-khao-sat-viec-lam-SVTN' . $yearString . '.zip';
    //     $zipFilePath = storage_path('app/temp/' . $zipFileName);

    //     if (!File::exists(storage_path('app/temp'))) {
    //         File::makeDirectory(storage_path('app/temp'), 0755, true);
    //     }

    //     $zip = new ZipArchive;
    //     if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    //         return back()->with('error', 'Không thể tạo file zip.');
    //     }

    //     foreach ($responses as $response) {

    //         $viewData = [
    //             'response' => $response,
    //             'student' => $response->student,
    //             'survey' => $survey,
    //             'major' => $major,
    //         ];

    //         $pdf = Pdf::loadView('admin.pages.admin.survey.result_detail_2', $viewData)
    //             ->setOptions([
    //                 'defaultFont' => 'DejaVu Sans',
    //                 'isHtml5ParserEnabled' => true,
    //                 'isPhpEnabled' => true,
    //                 'isRemoteEnabled' => true,
    //                 'defaultPaperSize' => 'a4',
    //             ]);

    //         $pdfContent = $pdf->output();
    //         $pdfFileName = $response->code_student . '_' . $response->full_name . '.pdf';
    //         $zip->addFromString($pdfFileName, $pdfContent);
    //     }

    //     $zip->close();

    //     return response()
    //         ->download($zipFilePath, $zipFileName)
    //         ->deleteFileAfterSend(true);
    // }
}
