<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SurveyExport implements FromQuery, WithHeadings, WithMapping
{
    protected ?int $surveyId;
    protected ?int $graduationId;


    public function __construct(?int $surveyId = null, ?int $graduationId = null)
    {
        $this->surveyId     = $surveyId;
        $this->graduationId = $graduationId;
    }


    public function query()
    {
        $q = DB::table('employment_survey_responses_v2');

        if ($this->surveyId) {
            $q->where('survey_period_id', $this->surveyId);
        }
        if ($this->graduationId) {
            $q->where('graduation_id', $this->graduationId);
        }

        return $q->select([
            'id',
            'code_student',
            'full_name',
            'gender',
            'dob',
            'email',
            'phone_number',
            'training_industry_id',
            'employment_status',
            'work_area',
            'work_location',
            'city_work_id',
            'company_name',
            'salary',
            'created_at',
        ]);
    }

    /**
     * Hàm này tạo ra dòng tiêu đề.
     */
    public function headings(): array
    {
        return [
            'ID',
            'Mã SV',
            'Họ tên',
            'Giới tính',
            'Ngày sinh',
            'Email',
            'SĐT',
            'Mã ngành đào tạo',
            'Tình trạng việc làm',
            'Khu vực làm việc',
            'Nơi làm việc (text)',
            'Mã tỉnh nơi làm việc',
            'Công ty',
            'Mức lương',
            'Ngày tạo',
        ];
    }


    public function map($row): array
    {
         $employmentStatusText = [
            1 => 'Đã có việc làm',
            2 => 'Tiếp tục học',
            3 => 'Chưa có việc làm'
        ];

        return [
            $row->id,
            $row->code_student,
            $row->full_name,
            $row->gender,
            optional($row->dob) ? \Carbon\Carbon::parse($row->dob)->format('d/m/Y') : null,
            $row->email,
            "'" . $row->phone_number, // Thêm dấu ' để Excel không tự đổi định dạng số điện thoại
            $row->training_industry_id,
            $employmentStatusText[$row->employment_status] ?? 'Không xác định', // Hiển thị text thay vì số
            $row->work_area,
            $row->work_location,
            $row->city_work_id,
            $row->company_name,
            $row->salary,
            optional($row->created_at) ? \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') : null,
        ];
    }
}
