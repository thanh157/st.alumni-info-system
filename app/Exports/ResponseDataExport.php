<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ResponseDataExport implements FromCollection, WithHeadings, WithTitle, WithStyles, WithColumnWidths
{
    protected $r2;

    protected $majorMap = [
        1 => '7480201 - Công nghệ thông tin',
        2 => '7480102 - Mạng máy tính và truyền thông dữ liệu',
        3 => '7480112 - Hệ thống thông tin',
    ];

    public function __construct($r2)
    {
        $this->r2 = $r2;
    }

    public function collection()
    {
        $data = collect();

        foreach ($this->r2 as $index => $item) {
            $data->push([
                $index + 1,
                $item->code_student,
                $item->full_name,
                !empty($item->dob) ? date('d/m/Y', strtotime($item->dob)) : '',
                $item->gender == 'Nam' ? 'Nam' : 'Nữ',
                $item->identification_card_number,
                $item->identification_card_number_update,
                $item->identification_issuance_place,
                !empty($item->identification_issuance_date) ? date('d/m/Y', strtotime($item->identification_issuance_date)) : '',
                $this->majorMap[$item->training_industry_id] ?? '',
                $item->course,
                $item->phone_number,
                $item->email,
                $this->label('employment_status', $item->employment_status),
                $item->recruit_partner_name,
                $item->recruit_partner_address,
                !empty($item->recruit_partner_date) ? date('d/m/Y', strtotime($item->recruit_partner_date)) : '',
                $item->recruit_partner_position,
                $this->label('work_area', $item->work_area),
                $this->label('employed_since', $item->employed_since),
                $this->label('trained_field', $item->trained_field),
                $this->label('professional_qualification_field', $item->professional_qualification_field),
                $this->label('level_knowledge_acquired', $item->level_knowledge_acquired),
                $item->starting_salary,
                $this->label('average_income', $item->average_income),
                $this->labelList('recruitment_type', $item->recruitment_type),
                $this->labelList('job_search_method', $item->job_search_method),
                $this->labelList('soft_skills_required', $item->soft_skills_required),
                $this->labelList('must_attended_courses', $item->must_attended_courses),
                $this->labelList('solutions_get_job', $item->solutions_get_job),
                $item->city_work_id,
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
            'Số CCCD/CMTND (cập nhật)',
            'Nơi cấp CCCD/CMTND',
            'Ngày cấp CCCD/CMTND',
            'Ngành đào tạo',
            'Khóa học',
            'Điện thoại',
            'Email',
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
            'Mã tỉnh/thành làm việc',
        ];
    }

    public function title(): string
    {
        return 'Dữ liệu khảo sát';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5, 'B' => 12, 'C' => 22, 'D' => 12, 'E' => 8, 'F' => 18, 'G' => 18, 'H' => 25, 'I' => 15,
            'J' => 35, 'K' => 15, 'L' => 15, 'M' => 25, 'N' => 18, 'O' => 25, 'P' => 30, 'Q' => 15,
            'R' => 22, 'S' => 18, 'T' => 25, 'U' => 25, 'V' => 25, 'W' => 30, 'X' => 18, 'Y' => 20,
            'Z' => 30, 'AA' => 25, 'AB' => 35, 'AC' => 35, 'AD' => 40, 'AE' => 15,
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
