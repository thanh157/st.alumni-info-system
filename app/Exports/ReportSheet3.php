<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class ReportSheet3 implements FromCollection, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $schoolYear;
    protected $r2;
    protected $majors;

    public function __construct($schoolYear, $r2, $majors)
    {
        $this->schoolYear = $schoolYear;
        $this->r2 = $r2;
        $this->majors = $majors;
    }

    public function collection()
    {
        $data = collect([
            ['HỌC VIỆN NÔNG NGHIỆP VIỆT NAM'],
            ['KHOA CÔNG NGHỆ THÔNG TIN'],
            [''],
            ['DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM ' . $this->schoolYear . ' PHẢN HỒI VỀ TÌNH HÌNH VIỆC LÀM'],
            [''],

            // Row 6: Header Level 1
            [
                'TT', 'Mã sinh viên', 'Họ và tên', 'Ngày sinh', 'Giới tính',
                'Số thẻ' . "\n" . 'CCCD/CMTND',
                'Mã ngành đào tạo' . "\n" . '(Ghi bằng số theo mã ngành tuyển sinh)',
                'Điện thoại', 'Email',
                'Tình hình việc làm', '', '', '', '',
                'Khu vực làm việc', '', '', '',
                'Nơi làm việc' . "\n" . '(Tỉnh/TP)' . "\n" . 'Ghi tên tỉnh',
                'Thời gian tìm được việc làm sau tốt nghiệp', '', '', '',
                'Sinh viên có học được kiến thức, kỹ năng cần thiết từ nhà trường', '', '',
                'Mức lương khởi điểm/1 tháng (triệu đồng)',
                'Thu nhập bình quân/1 tháng', '', '', '',
                'Hình thức tìm việc làm', '', '', '', '',
                'Hình thức tuyển dụng', '', '', '', '', '',
                'Kỹ năng mềm cần thiết cho công việc', '', '', '', '', '', '', '', '',
                'Khóa học đã tham gia sau khi tốt nghiệp để đáp ứng yêu cầu công việc', '', '', '', '', '',
                'Giải pháp tăng tỷ lệ sinh viên có việc làm đúng ngành đào tạo', '', '', '', '', ''
            ],

            // Row 7: Header Level 2
            [
                '', '', '', '', '', '', '', '', '',
                'Có việc làm', '', '', 'Tiếp tục học', 'Chưa có việc làm',
                'Nhà nước', 'Tư nhân', 'Tự tạo việc làm', 'Có yếu tố nước ngoài', '',
                'Dưới 3 tháng', 'Từ 3 tháng đến dưới 6 tháng', 'Từ 6 tháng đến dưới 12 tháng', 'Từ 12 tháng trở lên',
                'Đã học được', 'Chỉ học được một phần', 'Không học được', '',
                'Dưới 5 triệu đồng', 'Từ 5 triệu đến 10 triệu đồng', 'Từ trên 10 triệu đến 15 triệu đồng', 'Từ 15 triệu đồng trở lên',
                'Do Học viện/khoa giới thiệu', 'Bạn bè, người quen giới thiệu', 'Tự tìm việc làm', 'Tự tạo việc làm', 'Hình thức khác',
                'Thi tuyển', 'Hợp đồng', 'Điều động', 'Xét tuyển', 'Biệt phái', 'Hình thức khác',
                'Kỹ năng giao tiếp', 'Kỹ năng thuyết trình', 'Kỹ năng làm việc nhóm', 'Kỹ năng viết báo cáo tài liệu',
                'Kỹ năng lãnh đạo', 'Kỹ năng Tiếng Anh', 'Kỹ năng Tin học', 'Kỹ năng hội nhập quốc tế', 'Kỹ năng khác',
                'Nâng cao kiến thức chuyên môn', 'Nâng cao kỹ năng chuyên môn nghiệp vụ', 'Nâng cao về kỹ năng công nghệ thông tin',
                'Nâng cao kỹ năng ngoại ngữ', 'Phát triển kỹ năng quản lý', 'Tiếp tục học thạc sĩ, tiến sĩ',
                'Học viện tổ chức các buổi trao đổi, chia sẻ kinh nghiệm tìm kiếm việc làm giữa cựu sinh viên với sinh viên',
                'Học viện tổ chức các buổi trao đổi giữa đơn vị sử dụng lao động với sinh viên',
                'Đơn vị sử dụng lao động tham gia vào quá trình đào tạo',
                'Chương trình đào tạo được điều chỉnh và cập nhật theo nhu cầu của thị trường lao động',
                'Tăng cường các hoạt động thực hành và chuyên môn tại cơ sở', 'Giải pháp khác'
            ],

            // Row 8: Header Level 3
            [
                '', '', '', '', '', '', '', '', '',
                'Đúng ngành đào tạo', 'Liên quan đến ngành đào tạo', 'Không liên quan đến ngành đào tạo', '', '',
                'Nhà nước', 'Tư nhân', 'Tự tạo việc làm', 'Có yếu tố nước ngoài', '',
                'Dưới 3 tháng', 'Từ 3 tháng đến dưới 6 tháng', 'Từ 6 tháng đến dưới 12 tháng', 'Từ 12 tháng trở lên',
                'Đã học được', 'Chỉ học được một phần', 'Không học được', '',
                'Dưới 5 triệu', 'Từ 5 triệu đến dưới 10 triệu', 'Từ 10 triệu đến dưới 15 triệu', 'Từ 15 triệu trở lên',
                'Do Học viện/khoa giới thiệu', 'Bạn bè, người quen giới thiệu', 'Tự tìm việc làm', 'Tự tạo việc làm', 'Hình thức khác',
                'Thi tuyển', 'Hợp đồng', 'Điều động', 'Xét tuyển', 'Biệt phái', 'Hình thức khác',
                'Kỹ năng giao tiếp', 'Kỹ năng thuyết trình', 'Kỹ năng làm việc nhóm', 'Kỹ năng viết báo cáo tài liệu',
                'Kỹ năng lãnh đạo', 'Kỹ năng Tiếng Anh', 'Kỹ năng Tin học', 'Kỹ năng hội nhập quốc tế', 'Kỹ năng khác',
                'Nâng cao kiến thức chuyên môn', 'Nâng cao kỹ năng chuyên môn nghiệp vụ', 'Nâng cao kỹ năng về công nghệ thông tin',
                'Nâng cao kỹ năng ngoại ngữ', 'Phát triển kỹ năng quản lý', 'Tiếp tục học thạc sĩ, tiến sĩ',
                'Học viện tổ chức các buổi trao đổi, chia sẻ kinh nghiệm tìm kiếm việc làm giữa cựu sinh viên với sinh viên',
                'Học viện tổ chức các buổi trao đổi giữa đơn vị sử dụng lao động với sinh viên',
                'Đơn vị sử dụng lao động tham gia vào quá trình đào tạo',
                'Chương trình đào tạo được điều chỉnh và cập nhật theo nhu cầu của thị trường lao động',
                'Tăng cường các hoạt động thực hành và chuyên môn tại cơ sở', 'Giải pháp khác', ''
            ],
        ]);

        // Add response data
        foreach ($this->r2 as $index => $item) {
            $major = $this->majors->get($item->training_industry_id);

            // Parse JSON fields
            $recruitmentType = json_decode($item->recruitment_type, true);
            $jobSearchMethod = json_decode($item->job_search_method, true);
            $softSkills = json_decode($item->soft_skills_required, true);
            $attendedCourses = json_decode($item->must_attended_courses, true);
            $solutions = json_decode($item->solutions_get_job, true);

            $row = [
                $index + 1,
                $item->code_student,
                $item->full_name,
                !empty($item->dob) ? date('d-m-Y', strtotime($item->dob)) : '',
                $item->gender == 'male' ? 'Nam' : 'Nữ',
                $item->identification_card_number,
                optional($major)->code,
                $item->phone_number,
                $item->email,

                // Tình hình việc làm - FIX EMPLOYMENT_STATUS
                $item->trained_field == 1 ? 'x' : '',
                $item->trained_field == 2 ? 'x' : '',
                $item->trained_field == 3 ? 'x' : '',
                $item->employment_status == 3 ? 'x' : '', // FIX: 3 = tiếp tục học
                !in_array($item->employment_status, [1, 3]) ? 'x' : '', // FIX: Chưa có việc

                // Khu vực làm việc
                $item->work_area == '1' ? 'x' : '',
                $item->work_area == '2' ? 'x' : '',
                $item->work_area == '3' ? 'x' : '',
                $item->work_area == '4' ? 'x' : '',

                $item->city_work_id ?? '',

                // Thời gian tìm việc (4 cột)
                $item->employed_since == 1 ? 'x' : '',
                $item->employed_since == 2 ? 'x' : '',
                $item->employed_since == 3 ? 'x' : '',
                $item->employed_since == 4 ? 'x' : '',

                // Kiến thức kỹ năng (3 cột)
                $item->level_knowledge_acquired == 1 ? 'x' : '',
                $item->level_knowledge_acquired == 2 ? 'x' : '',
                $item->level_knowledge_acquired == 3 ? 'x' : '',

                $item->starting_salary ?? '',

                // Thu nhập (4 cột)
                $item->average_income == 1 ? 'x' : '',
                $item->average_income == 2 ? 'x' : '',
                $item->average_income == 3 ? 'x' : '',
                $item->average_income == 4 ? 'x' : '',
            ];

            // Hình thức tìm việc (5 cột)
            foreach (range(1, 5) as $k) {
                $row[] = in_array($k, data_get($jobSearchMethod, 'value', [])) ? 'x' : '';
            }

            // Hình thức tuyển dụng (6 cột)
            foreach (range(1, 6) as $k) {
                $row[] = in_array($k, data_get($recruitmentType, 'value', [])) ? 'x' : '';
            }

            // Kỹ năng mềm (9 cột)
            foreach (range(1, 9) as $k) {
                $row[] = in_array($k, data_get($softSkills, 'value', [])) ? 'x' : '';
            }

            // Khóa học (6 cột)
            foreach (range(1, 6) as $k) {
                $row[] = in_array($k, data_get($attendedCourses, 'value', [])) ? 'x' : '';
            }

            // Giải pháp (6 cột)
            foreach (range(1, 6) as $k) {
                $row[] = in_array($k, data_get($solutions, 'value', [])) ? 'x' : '';
            }

            $data->push($row);
        }

        return $data;
    }

    public function title(): string
    {
        return 'Mẫu báo cáo 3';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5, 'B' => 12, 'C' => 20, 'D' => 12, 'E' => 10, 'F' => 15, 'G' => 12, 'H' => 12, 'I' => 20,
            'J' => 12, 'K' => 12, 'L' => 12, 'M' => 12, 'N' => 12, 'O' => 10, 'P' => 10, 'Q' => 12, 'R' => 12,
            'S' => 15, 'T' => 10, 'U' => 10, 'V' => 10, 'W' => 10, 'X' => 12, 'Y' => 12, 'Z' => 12, 'AA' => 12,
            'AB' => 10, 'AC' => 10, 'AD' => 10, 'AE' => 10, 'AF' => 12, 'AG' => 12, 'AH' => 12, 'AI' => 12, 'AJ' => 12,
            'AK' => 10, 'AL' => 10, 'AM' => 10, 'AN' => 10, 'AO' => 10, 'AP' => 10, 'AQ' => 10, 'AR' => 10, 'AS' => 10,
            'AT' => 12, 'AU' => 10, 'AV' => 10, 'AW' => 10, 'AX' => 10, 'AY' => 10, 'AZ' => 12, 'BA' => 12, 'BB' => 12,
            'BC' => 12, 'BD' => 12, 'BE' => 12, 'BF' => 15, 'BG' => 15, 'BH' => 15, 'BI' => 15, 'BJ' => 15, 'BK' => 12,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['size' => 14, 'bold' => true, 'name' => 'Times New Roman'], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            2 => ['font' => ['size' => 14, 'bold' => true, 'underline' => true, 'name' => 'Times New Roman'], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            4 => ['font' => ['size' => 15, 'bold' => true, 'name' => 'Times New Roman'], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]],
            6 => ['font' => ['bold' => true, 'size' => 10, 'name' => 'Times New Roman'], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']]],
            7 => ['font' => ['bold' => true, 'size' => 10, 'name' => 'Times New Roman'], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']]],
            8 => ['font' => ['bold' => true, 'size' => 10, 'name' => 'Times New Roman'], 'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true], 'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']]],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle('A1:BK' . $lastRow)->getFont()->setName('Times New Roman')->setSize(11);

                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A4:P4');

                // Merge header table
                $sheet->mergeCells('A6:A8');
                $sheet->mergeCells('B6:B8');
                $sheet->mergeCells('C6:C8');
                $sheet->mergeCells('D6:D8');
                $sheet->mergeCells('E6:E8');
                $sheet->mergeCells('F6:F8');
                $sheet->mergeCells('G6:G8');
                $sheet->mergeCells('H6:H8');
                $sheet->mergeCells('I6:I8');
                $sheet->mergeCells('J6:N6');
                $sheet->mergeCells('J7:L7');
                $sheet->mergeCells('M7:M8');
                $sheet->mergeCells('N7:N8');
                $sheet->mergeCells('O6:R7');
                $sheet->mergeCells('S6:S8');
                $sheet->mergeCells('T6:W7');
                $sheet->mergeCells('X6:Z7');
                $sheet->mergeCells('AA6:AA8');
                $sheet->mergeCells('AB6:AE7');
                $sheet->mergeCells('AF6:AJ7');
                $sheet->mergeCells('AK6:AP7');
                $sheet->mergeCells('AQ6:AY7');
                $sheet->mergeCells('AZ6:BE7');
                $sheet->mergeCells('BF6:BK7');

                // Borders
                $sheet->getStyle('A6:BK' . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true]
                ]);

                $sheet->getRowDimension(6)->setRowHeight(50);
                $sheet->getRowDimension(7)->setRowHeight(40);
                $sheet->getRowDimension(8)->setRowHeight(30);

                // Chữ ký
                $signatureRow = $lastRow + 4;
                $richText = new RichText();
                $year = date('Y');
                $part1 = $richText->createTextRun("Hà Nội, ngày    tháng    năm {$year}\n\n");
                $part1->getFont()->setName('Times New Roman')->setItalic(true);
                $part2 = $richText->createTextRun("TRƯỞNG KHOA");
                $part2->getFont()->setBold(true)->setName('Times New Roman');
                $sheet->setCellValue('K' . $signatureRow, $richText);
                $sheet->mergeCells('K' . $signatureRow . ':R' . ($signatureRow + 4));
                $sheet->getStyle('K' . $signatureRow)->getAlignment()->setVertical(Alignment::VERTICAL_TOP)->setHorizontal(Alignment::HORIZONTAL_CENTER)->setWrapText(true);
            },
        ];
    }
}