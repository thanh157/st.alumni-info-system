<?php

namespace App\Http\Controllers;

use App\Models\ContactSurvey;
use App\Models\EmploymentSurveyResponse;
use App\Models\Major;
use App\Models\Student;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class KhaoSatController extends Controller
{
    public function showForm($id)
    {
        $survey = Survey::with('questions')->findOrFail($id);
        if ($survey->isInActive()) {
            abort(404);
        }
        $student = Student::where('code', 596606)->first();
        $major = Major::query()->get();


        // if ($student) {
        //     $student->gender = $student->gender === 'male' ? 'Nam' : ($student->gender === 'female' ? 'Nữ' : '');
        // }

        $end_time = $survey->end_time;
        $current_time = date('Y-m-d H:i:s');

        $outDate = strtotime($current_time) > strtotime($end_time);
        $viewData = [
            'survey' => $survey,
            'student' => $student,
            'major' => $major,
            'outDate' => $outDate,
        ];
        return view('admin.pages.admin.my_form', $viewData);
    }

    public function verify(Request $request)
    {
        try {
            $full_name = $request->input('full_name');
            $mssv = $request->input('mssv');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $dob = $request->input('dob');



            // Lấy student dựa trên MSSV nếu có, hoặc fullname nếu MSSV trống
            $student = Student::query();

            if (!empty($email)) {
                $student->where('email', $email);
            }

            if (!empty($phone)) {
                $student->where('phone', $phone);
            }
            if (!empty($mssv)) {
                $student->where('code', $mssv);
            }

            if (!empty($full_name)) {
                $student->where('full_name', $full_name);
            }

            if (!empty($dob)) {
                $student->where('dob', $dob);
            }

            $student = $student->first();
            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anh/chị vui lòng kiểm tra lại thông tin đã nhập.',
                ]);
            }


            // $invalidFields = [];
            // if ($email && $student->email !== $email) $invalidFields[] = 'email';
            // if ($phone && $student->phone_number !== $phone) $invalidFields[] = 'phone';
            // if ($dob && $student->dob !== $dob) $invalidFields[] = 'dob';

            // if (count($invalidFields) > 0) {
            //     return response()->json([
            //         'success' => false,
            //         'invalid_fields' => $invalidFields,
            //         'message' => 'Thông tin không khớp: ' . implode(', ', $invalidFields),
            //     ]);
            // }

            return response()->json([
                'success' => true,
                'student' => $student,
            ]);
        } catch (\Exception $e) {
            \Log::error($e);
            return response()->json([
                'success' => false,
                'message' => 'Đã có lỗi xảy ra, vui lòng thử lại.',
            ]);
        }
    }

    public function verifyV2(Request $request)
    {
        try {
            $surveyId = $request->input('survey_id');

            $code = $request->input('m_mssv');
            $phone = $request->input('m_phone');
            $email = $request->input('m_email');
            $dob = $request->input('m_dob');
            $industry_id = $request->input('training_industry');

            // Kiểm tra mã sinh viên có nhập không
            if (empty($code)) {
                return redirect()->back()->with('error', 'Vui lòng nhập mã sinh viên (MSSV)');
            }

            // Kiểm tra survey có tồn tại không
            $survey = ContactSurvey::find($surveyId);
            if (empty($survey)) {
                return redirect()->back()->with('error', 'Khảo sát không tồn tại');
            }

            $student = Student::query();

            if (!empty($email)) {
                $student->where('email', $email);
            }
            if (!empty($dod)) {
                $student->where('dod', $dod);
            }
            if (!empty($phone)) {
                $student->where('phone_number', $phone);
            }
            if (!empty($code)) {
                $student->where('code', $code);
            }

            if (!empty($fullname)) {
                $student->where('full_name', $fullname);
            }
            $student = $student->first();
            dd($student);
            die();

            // ->where('code', $code)
            // ->first();

            if (!$student) {
                return redirect()->back()->with('error', 'Không tìm thấy sinh viên có mã này trong khảo sát')->withInput();
            }


            $invalidFields = [];

            if (!empty($email) && $student->email !== $email) {
                $invalidFields[] = 'email';
            }

            if (!empty($dob) && $student->dob !== $dob) {
                $invalidFields[] = 'dob';
            }

            if (!empty($phone) && $student->phone_number !== $phone) {
                $invalidFields[] = 'phone';
            }

            if (!empty($industry_id) && $student->training_industry_id != $industry_id) {
                $invalidFields[] = 'training_industry_id';
            }

            if (count($invalidFields) > 0) {
                return redirect()->back()->with('error', 'Thông tin xác thực không khớp: ' . implode(',', $invalidFields))->withInput();
            }

            $id = $surveyId;
            session()->put("verified_{$id}", true);
            session()->put("student_code_{$id}", $student->code);
            session()->put("email_{$id}", $student->email);

            return redirect()->route('admin.contact-survey.form', ['id' => $id]);

            return response()->json([
                'success' => true,
                'student' => $student,
            ]);
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->withInput()->with('error', 'Không tìm thấy sinh viên có mã này trong khảo sát');
        }
    }

    public function submit(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'survey_id' => 'required|exists:survey,id',
            ], [], []);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $recruitmentValue = json_encode(['value' => $request->input('recruitment_type', []), 'content_other' => request('recruitment_type_other')]);
            $soft_skills_required = json_encode(['value' => $request->input('soft_skills_required', []), 'content_other' => request('soft_skills_required_other')]);
            $must_attended_courses = json_encode(['value' => $request->input('must_attended_courses', []), 'content_other' => request('must_attended_courses_other')]);
            $solutions_get_job = json_encode(['value' => $request->input('solutions_get_job', []), 'content_other' => request('solutions_get_job_other')]);
            $job_search_method = json_encode(['value' => $request->input('job_search_method', []), 'content_other' => request('job_search_method_other')]);

            EmploymentSurveyResponse::updateOrCreate([
                'survey_period_id' => request('survey_id'),
                'student_id' => request('student_id'),
                'code_student' => request('code_student'),
            ], [
                'full_name' => request('full_name'),
                'email' => request('email'),
                'gender' => request('gender'),
                'dob' => request('dob'),
                'identification_card_number' => request('identification_card_number'),
                'identification_issuance_place' => request('identification_issuance_place'),
                'identification_issuance_date' => request('identification_issuance_date'),
                'training_industry_id' => request('training_industry_id'),
                'phone_number' => request('phone_number'),
                'course' => request('course'),
                'employment_status' => request('employment_status'),
                'recruit_partner_name' => request('recruit_partner_name'),
                'recruit_partner_address' => request('recruit_partner_address'),
                'recruit_partner_date' => request('recruit_partner_date'),
                'recruit_partner_position' => request('recruit_partner_position'),
                'work_area' => request('work_area'),
                'employed_since' => request('employed_since'),
                'trained_field' => request('trained_field'),
                'professional_qualification_field' => request('professional_qualification_field'),
                'level_knowledge_acquired' => request('level_knowledge_acquired'),
                'starting_salary' => request('starting_salary'),
                'average_income' => request('average_income'),
                'recruitment_type' => $recruitmentValue,
                'soft_skills_required' => $soft_skills_required,
                'must_attended_courses' => $must_attended_courses,
                'solutions_get_job' => $solutions_get_job,
                'job_search_method' => $job_search_method,
            ]);

            return redirect()->route('survey.thankyou')->with('success', 'Ghi nhận khảo sát thành công!');
        } catch (\Exception $e) {
            Log::error($e);
            return redirect()->back()->with('error', 'System error');
        }
    }

    public function sendMail($id)
    {
        try {
            // @todo get all student by survey
            // send mail to all
        } catch (\Exception $e) {
            Log::error($e);
        }
    }
}
