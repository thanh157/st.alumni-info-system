<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmploymentSurveyResponse extends Model
{
    use HasFactory;

    protected $table = 'employment_survey_responses_v2';

    protected $fillable = [
        'survey_period_id',
        'student_id',
        'email',
        'full_name',
        'dob',
        'gender',
        'code_student',
        'identification_card_number',
        'identification_card_number_update',
        'identification_issuance_place',
        'identification_issuance_date',
        'training_industry_id',
        'course',
        'phone_number',
        'employment_status',
        'recruit_partner_name',
        'recruit_partner_address',
        'recruit_partner_date',
        'recruit_partner_position',
        'work_area',
        'employed_since',
        'trained_field',
        'professional_qualification_field',
        'level_knowledge_acquired',
        'starting_salary',
        'average_income',
        'recruitment_type',
        'job_search_method',
        'soft_skills_required',
        'must_attended_courses',
        'solutions_get_job',
        'city_work_id',
    ];

    protected $casts = [
        'dob' => 'date',
        'identification_issuance_date' => 'date',
        'recruit_partner_date' => 'date',
        'recruitment_type' => 'array',
        'job_search_method' => 'array',
        'soft_skills_required' => 'array',
        'must_attended_courses' => 'array',
        'solutions_get_job' => 'array',
    ];


    public function surveyPeriod()
    {
        return $this->belongsTo(Survey::class, 'survey_period_id');
    }


    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }


    public function major()
    {
        return $this->belongsTo(Major::class, 'training_industry_id');
    }


    public function city()
    {
        return $this->belongsTo(City::class, 'city_work_id');
    }


    public function getGenderTextAttribute()
    {
        return $this->gender === 'male' ? 'Nam' : ($this->gender === 'female' ? 'Nữ' : '');
    }


    public function getEmploymentStatusTextAttribute()
    {
        $statuses = [
            1 => 'Đang có việc làm',
            2 => 'Tiếp tục học',
            3 => 'Chưa có việc làm',
            4 => 'Không phản hồi',
        ];

        return $statuses[$this->employment_status] ?? '';
    }


    public function getTrainedFieldTextAttribute()
    {
        $fields = [
            1 => 'Đúng ngành',
            2 => 'Liên quan',
            3 => 'Không liên quan',
        ];

        return $fields[$this->trained_field] ?? '';
    }


    public function getWorkAreaTextAttribute()
    {
        $areas = [
            '1' => 'Khu vực nhà nước',
            '2' => 'Khu vực tư nhân',
            '3' => 'Tự tạo việc làm',
            '4' => 'Có yếu tố nước ngoài',
        ];

        return $areas[$this->work_area] ?? '';
    }


    public function getEmployedSinceTextAttribute()
    {
        $times = [
            1 => 'Dưới 3 tháng',
            2 => 'Từ 3 đến 6 tháng',
            3 => 'Từ 6 đến 12 tháng',
            4 => 'Trên 12 tháng',
        ];

        return $times[$this->employed_since] ?? '';
    }


    public function getAverageIncomeTextAttribute()
    {
        $incomes = [
            1 => 'Dưới 5 triệu',
            2 => 'Từ 5 đến 10 triệu',
            3 => 'Từ 10 đến 15 triệu',
            4 => 'Trên 15 triệu',
        ];

        return $incomes[$this->average_income] ?? '';
    }


    public function parseJsonField($field)
    {
        try {
            $data = is_string($this->$field) ? json_decode($this->$field, true) : $this->$field;
            return is_array($data) ? $data : ['value' => []];
        } catch (\Exception $e) {
            return ['value' => []];
        }
    }


    public function hasJsonValue($field, $value)
    {
        $data = $this->parseJsonField($field);
        return in_array($value, data_get($data, 'value', []));
    }
}
