<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ResponseDataExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $studentTab2;
    protected $responsesByCode;

    protected $majorCodeMap = [
        1 => '7480201',
        2 => '7480102',
        3 => '7480112',
    ];

    protected $majorNameMap = [
        1 => 'Công nghệ thông tin',
        2 => 'Mạng máy tính và truyền thông dữ liệu',
        3 => 'Hệ thống thông tin',
    ];

    public function __construct($studentTab2, $responsesByCode)
    {
        $this->studentTab2 = $studentTab2;
        $this->responsesByCode = $responsesByCode;
    }

    public function collection()
    {
        $data = collect();

        foreach ($this->studentTab2 as $index => $student) {
            $code = $student->code ?? '';
            $response = $this->responsesByCode->get($code);
            $hasResponse = !is_null($response);

            $dob = $response->dob ?? ($student->dob ?? null);
            $gender = $hasResponse
                ? ($response->gender == 'Nam' ? 'Nam' : 'Nữ')
                : (($student->gender ?? '') == 'female' ? 'Nữ' : (($student->gender ?? '') == 'male' ? 'Nam' : ''));

            $cccd = $response->identification_card_number ?? ($student->citizen_identification ?? '');
            $issuancePlace = $response->identification_issuance_place ?? '';
            $issuanceDate = $response->identification_issuance_date ?? null;

            $majorCode = $hasResponse
                ? ($this->majorCodeMap[$response->training_industry_id] ?? ($student->industry_code ?? ''))
                : ($student->industry_code ?? '');
            $majorName = $hasResponse
                ? ($this->majorNameMap[$response->training_industry_id] ?? ($student->industry_name ?? ''))
                : ($student->industry_name ?? '');

            $course = $response->course ?? '';
            $phone = $response->phone_number ?? ($student->phone ?? '');
            $email = $response->email ?? ($student->email ?? '');

            $data->push([
                $index + 1,
                $code,
                $response->full_name ?? ($student->full_name ?? ''),
                !empty($dob) ? date('d/m/Y', strtotime($dob)) : '',
                $gender,
                $cccd,
                $issuancePlace,
                !empty($issuanceDate) ? date('d/m/Y', strtotime($issuanceDate)) : '',
                'Công nghệ thông tin',
                $majorCode,
                $majorName,
                $course,
                $phone,
                $email,
                $hasResponse ? 'Đã khảo sát' : 'Chưa khảo sát',
                $hasResponse ? $this->label('employment_status', $response->employment_status) : '',
                $hasResponse ? $response->recruit_partner_name : '',
                $hasResponse ? $response->recruit_partner_address : '',
                $hasResponse && !empty($response->recruit_partner_date) ? date('d/m/Y', strtotime($response->recruit_partner_date)) : '',
                $hasResponse ? $response->recruit_partner_position : '',
                $hasResponse ? $this->label('work_area', $response->work_area) : '',
                $hasResponse ? $this->label('employed_since', $response->employed_since) : '',
                $hasResponse ? $this->label('trained_field', $response->trained_field) : '',
                $hasResponse ? $this->label('professional_qualification_field', $response->professional_qualification_field) : '',
                $hasResponse ? $this->label('level_knowledge_acquired', $response->level_knowledge_acquired) : '',
                $hasResponse ? $response->starting_salary : '',
                $hasResponse ? $this->label('average_income', $response->average_income) : '',
                $hasResponse ? $this->labelList('recruitment_type', $response->recruitment_type) : '',
                $hasResponse ? $this->labelList('job_search_method', $response->job_search_method) : '',
                $hasResponse ? $this->labelList('soft_skills_required', $response->soft_skills_required) : '',
                $hasResponse ? $this->labelList('must_attended_courses', $response->must_attended_courses) : '',
                $hasResponse ? $this->labelList('solutions_get_job', $response->solutions_get_job) : '',
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'TT',
            'Mã sinh viên',
            'Họ và tên',
            'Ngày sinh',
            'Giới tính',
            'Số CCCD/CMTND',
            'Nơi cấp CCCD/CMTND',
            'Ngày cấp CCCD/CMTND',
            'Khoa',
            'Mã ngành',
            'Tên ngành',
            'Khóa học',
            'Điện thoại',
            'Email',
            'Trạng thái khảo sát',
            'Tình trạng việc làm',
            'Tên đơn vị tuyển dụng',
            'Địa chỉ đơn vị tuyển dụng',
            'Ngày tuyển dụng',
            'Chức vụ/Vị trí việc làm',
            'Khu vực làm việc',
            'Thời gian có việc làm sau tốt nghiệp',
            'Mức độ phù hợp với ngành đào tạo',
            'Mức độ phù hợp với trình độ chuyên môn',
            'Kiến thức, kỹ năng học được từ nhà trường',
            'Mức lương khởi điểm (triệu đồng)',
            'Thu nhập bình quân/tháng',
            'Hình thức tuyển dụng',
            'Hình thức tìm việc làm',
            'Kỹ năng mềm cần thiết cho công việc',
            'Khóa học cần tham gia thêm sau tốt nghiệp',
            'Giải pháp tăng tỷ lệ sinh viên có việc làm đúng ngành',
        ];
    }

    public function title(): string
    {
        return 'Dữ liệu khảo sát';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5, 'B' => 12, 'C' => 22, 'D' => 12, 'E' => 8, 'F' => 18, 'G' => 25, 'H' => 15,
            'I' => 18, 'J' => 12, 'K' => 35, 'L' => 15, 'M' => 15, 'N' => 25, 'O' => 15, 'P' => 18,
            'Q' => 25, 'R' => 30, 'S' => 15, 'T' => 22, 'U' => 18, 'V' => 25, 'W' => 25, 'X' => 25,
            'Y' => 30, 'Z' => 18, 'AA' => 20, 'AB' => 30, 'AC' => 25, 'AD' => 35, 'AE' => 35, 'AF' => 40,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            ],
        ];
    }

    private function label(string $key, $value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return config("config.$key.$value", '');
    }

    private function labelList(string $key, $json): string
    {
        $decoded = json_decode($json, true);
        $values = data_get($decoded, 'value', []);

        if (!is_array($values) || empty($values)) {
            return '';
        }

        $options = config("config.$key", []);

        $labels = array_map(function ($v) use ($options) {
            return $options[$v] ?? $v;
        }, $values);

        return implode(', ', $labels);
    }
}
