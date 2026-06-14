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
    protected $majorCodeMap = [
        1 => '7480201',
        2 => '7480102',
        3 => '7480112',
    ];

    // Bỏ $majors khỏi constructor
    public function __construct($schoolYear, $r2)
    {
        $this->schoolYear = $schoolYear;
        $this->r2 = $r2;
    }

    public function collection()
    {
        $data = collect([
            // Row 1: Header 1
            ['HỌC VIỆN NÔNG NGHIỆP VIỆT NAM'],

            // Row 2: Header 2
            ['KHOA CÔNG NGHỆ THÔNG TIN'],

            // Row 3: Tiêu đề chính
            ['DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM ' . $this->schoolYear . ' PHẢN HỒI VỀ TÌNH HÌNH VIỆC LÀM'],

            // Row 4: Dòng trống
            [''],

            // Row 5: Header Level 1
            [
                'TT',
                'Mã sinh viên',
                'Họ và tên',
                'Ngày sinh',
                'Giới tính',
                'Số thẻ' . "\n" . 'CCCD/CMTND',
                'Mã ngành đào tạo' . "\n" . '(Ghi bằng số theo mã ngành tuyển sinh)',
                'Điện thoại',
                'Email',
                'Tình hình việc làm',
                '',
                '',
                '',
                '',
                'Khu vực làm việc',
                '',
                '',
                '',
                'Nơi làm việc' . "\n" . '(Tỉnh/TP)' . "\n" . 'Ghi tên tỉnh',
                'Thời gian tìm được việc làm sau tốt nghiệp',
                '',
                '',
                '',
                'Sinh viên có học được kiến thức, kỹ năng cần thiết từ nhà trường',
                '',
                '',
                'Mức lương khởi điểm/1 tháng (triệu đồng)',
                'Thu nhập bình quân/1 tháng',
                '',
                '',
                '',
                'Hình thức tìm việc làm',
                '',
                '',
                '',
                '',
                'Hình thức tuyển dụng',
                '',
                '',
                '',
                '',
                '',
                'Kỹ năng mềm cần thiết cho công việc',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Khóa học đã tham gia sau khi tốt nghiệp để đáp ứng yêu cầu công việc',
                '',
                '',
                '',
                '',
                '',
                'Giải pháp tăng tỷ lệ sinh viên có việc làm đúng ngành đào tạo',
                '',
                '',
                '',
                '',
                '',
                'Ngày tuyển dụng'
            ],

            // Row 6: Header Level 2
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Có việc làm',
                '',
                '',
                'Tiếp tục học',
                'Chưa có việc làm',
                'Nhà nước',
                'Tư nhân',
                'Tự tạo việc làm',
                'Có yếu tố nước ngoài',
                '',
                'Dưới 3 tháng',
                'Từ 3 tháng đến dưới 6 tháng',
                'Từ 6 tháng đến dưới 12 tháng',
                'Từ 12 tháng trở lên',
                'Đã học được',
                'Chỉ học được một phần',
                'Không học được',
                '',
                'Dưới 5 triệu đồng',
                'Từ 5 triệu đến 10 triệu đồng',
                'Từ trên 10 triệu đến 15 triệu đồng',
                'Từ 15 triệu đồng trở lên',
                'Do Học viện/khoa giới thiệu',
                'Bạn bè, người quen giới thiệu',
                'Tự tìm việc làm',
                'Tự tạo việc làm',
                'Hình thức khác',
                'Thi tuyển',
                'Hợp đồng',
                'Điều động',
                'Xét tuyển',
                'Biệt phái',
                'Hình thức khác',
                'Kỹ năng giao tiếp',
                'Kỹ năng thuyết trình',
                'Kỹ năng làm việc nhóm',
                'Kỹ năng viết báo cáo tài liệu',
                'Kỹ năng lãnh đạo',
                'Kỹ năng Tiếng Anh',
                'Kỹ năng Tin học',
                'Kỹ năng hội nhập quốc tế',
                'Kỹ năng khác',
                'Nâng cao kiến thức chuyên môn',
                'Nâng cao kỹ năng chuyên môn nghiệp vụ',
                'Nâng cao về kỹ năng công nghệ thông tin',
                'Nâng cao kỹ năng ngoại ngữ',
                'Phát triển kỹ năng quản lý',
                'Tiếp tục học thạc sĩ, tiến sĩ',
                'Học viện tổ chức các buổi trao đổi, chia sẻ kinh nghiệm tìm kiếm việc làm giữa cựu sinh viên với sinh viên',
                'Học viện tổ chức các buổi trao đổi giữa đơn vị sử dụng lao động với sinh viên',
                'Đơn vị sử dụng lao động tham gia vào quá trình đào tạo',
                'Chương trình đào tạo được điều chỉnh và cập nhật theo nhu cầu của thị trường lao động',
                'Tăng cường các hoạt động thực hành và chuyên môn tại cơ sở',
                'Giải pháp khác',
                ''
            ],

            // Row 7: Header Level 3
            [
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                'Đúng ngành đào tạo',
                'Liên quan đến ngành đào tạo',
                'Không liên quan đến ngành đào tạo',
                '',
                '',
                'Nhà nước',
                'Tư nhân',
                'Tự tạo việc làm',
                'Có yếu tố nước ngoài',
                '',
                'Dưới 3 tháng',
                'Từ 3 tháng đến dưới 6 tháng',
                'Từ 6 tháng đến dưới 12 tháng',
                'Từ 12 tháng trở lên',
                'Đã học được',
                'Chỉ học được một phần',
                'Không học được',
                '',
                'Dưới 5 triệu',
                'Từ 5 triệu đến dưới 10 triệu',
                'Từ 10 triệu đến dưới 15 triệu',
                'Từ 15 triệu trở lên',
                'Do Học viện/khoa giới thiệu',
                'Bạn bè, người quen giới thiệu',
                'Tự tìm việc làm',
                'Tự tạo việc làm',
                'Hình thức khác',
                'Thi tuyển',
                'Hợp đồng',
                'Điều động',
                'Xét tuyển',
                'Biệt phái',
                'Hình thức khác',
                'Kỹ năng giao tiếp',
                'Kỹ năng thuyết trình',
                'Kỹ năng làm việc nhóm',
                'Kỹ năng viết báo cáo tài liệu',
                'Kỹ năng lãnh đạo',
                'Kỹ năng Tiếng Anh',
                'Kỹ năng Tin học',
                'Kỹ năng hội nhập quốc tế',
                'Kỹ năng khác',
                'Nâng cao kiến thức chuyên môn',
                'Nâng cao kỹ năng chuyên môn nghiệp vụ',
                'Nâng cao kỹ năng về công nghệ thông tin',
                'Nâng cao kỹ năng ngoại ngữ',
                'Phát triển kỹ năng quản lý',
                'Tiếp tục học thạc sĩ, tiến sĩ',
                'Học viện tổ chức các buổi trao đổi, chia sẻ kinh nghiệm tìm kiếm việc làm giữa cựu sinh viên với sinh viên',
                'Học viện tổ chức các buổi trao đổi giữa đơn vị sử dụng lao động với sinh viên',
                'Đơn vị sử dụng lao động tham gia vào quá trình đào tạo',
                'Chương trình đào tạo được điều chỉnh và cập nhật theo nhu cầu của thị trường lao động',
                'Tăng cường các hoạt động thực hành và chuyên môn tại cơ sở',
                'Giải pháp khác',
                ''
            ],

            // Row 8: Dòng số thứ tự (1), (2), (3)...
            [
                '(1)', '(2)', '(3)', '(4)', '(5)', '(6)', '(7)', '(8)', '(9)',
                '(10)', '(11)', '(12)', '(13)', '(14)', '(15)', '(16)', '(17)', '(18)', '(19)',
                '(20)', '(21)', '(22)', '(23)', '(24)', '(25)', '(26)', '(27)', '(28)', '(29)', '(30)', '(31)',
                '(32)', '(33)', '(34)', '(35)', '(36)', '(37)', '(38)', '(39)', '(40)', '(41)', '(42)',
                '(43)', '(44)', '(45)', '(46)', '(47)', '(48)', '(49)', '(50)', '(51)',
                '(52)', '(53)', '(54)', '(55)', '(56)', '(57)',
                '(58)', '(59)', '(60)', '(61)', '(62)', '(63)', '(64)'
            ],
        ]);

        // Add response data
        foreach ($this->r2 as $index => $item) {
            // Dùng majorCodeMap thay vì $majors
            $majorCode = $this->majorCodeMap[$item->training_industry_id] ?? '';

            // Parse JSON fields
            $recruitmentType = json_decode($item->recruitment_type, true);
            $jobSearchMethod = json_decode($item->job_search_method, true);
            $softSkills = json_decode($item->soft_skills_required, true);
            $attendedCourses = json_decode($item->must_attended_courses, true);
            $solutions = json_decode($item->solutions_get_job, true);

            $city = '';
            if (!empty($item->recruit_partner_address)) {
                $parts = explode(',', $item->recruit_partner_address);
                $city = trim(end($parts));
            }

            $cccd = $item->identification_card_number;
            if (!empty($cccd)) {
                $cccd = $cccd . ' ';
            }

            $row = [
                $index + 1,
                $item->code_student,
                $item->full_name,
                !empty($item->dob) ? date('d/m/Y', strtotime($item->dob)) : '',
                $item->gender == 'Nam' ? 'Nam' : 'Nữ',
                $cccd,
                $majorCode, // ← Dùng majorCodeMap
                $item->phone_number,
                $item->email,

                // Tình hình việc làm
                $item->trained_field == 1 ? 'x' : '',
                $item->trained_field == 2 ? 'x' : '',
                $item->trained_field == 3 ? 'x' : '',
                $item->employment_status == 2 ? 'x' : '',
                !in_array($item->employment_status, [1, 2]) ? 'x' : '',

                // Khu vực làm việc
                $item->work_area == '1' ? 'x' : '',
                $item->work_area == '2' ? 'x' : '',
                $item->work_area == '3' ? 'x' : '',
                $item->work_area == '4' ? 'x' : '',
                $city,

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

            // Ngày tuyển dụng
            $row[] = !empty($item->recruit_partner_date) ? date('d/m/Y', strtotime($item->recruit_partner_date)) : '';

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
            'BL' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['size' => 14, 'bold' => false, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            2 => [
                'font' => ['size' => 14, 'bold' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            3 => [
                'font' => ['size' => 14, 'bold' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ],
            5 => [
                'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true]
            ],
            6 => [
                'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true]
            ],
            7 => [
                'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true]
            ],
            8 => [
                'font' => ['bold' => false, 'size' => 12, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle('A1:BL' . $lastRow)->getFont()->setName('Times New Roman');

                if ($lastRow >= 9) {
                    $sheet->getStyle('A9:BL' . $lastRow)->getFont()->setSize(11);
                }

                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A3:S3');

                $sheet->mergeCells('A5:A7');
                $sheet->mergeCells('B5:B7');
                $sheet->mergeCells('C5:C7');
                $sheet->mergeCells('D5:D7');
                $sheet->mergeCells('E5:E7');
                $sheet->mergeCells('F5:F7');
                $sheet->mergeCells('G5:G7');
                $sheet->mergeCells('H5:H7');
                $sheet->mergeCells('I5:I7');
                $sheet->mergeCells('J5:N5');
                $sheet->mergeCells('J6:L6');
                $sheet->mergeCells('M6:M7');
                $sheet->mergeCells('N6:N7');
                $sheet->mergeCells('O5:R6');
                $sheet->mergeCells('S5:S7');
                $sheet->mergeCells('T5:W6');
                $sheet->mergeCells('X5:Z6');
                $sheet->mergeCells('AA5:AA7');
                $sheet->mergeCells('AB5:AE6');
                $sheet->mergeCells('AF5:AJ6');
                $sheet->mergeCells('AK5:AP6');
                $sheet->mergeCells('AQ5:AY6');
                $sheet->mergeCells('AZ5:BE6');
                $sheet->mergeCells('BF5:BK6');
                $sheet->mergeCells('BL5:BL7');

                $sheet->getStyle('A5:BL' . $lastRow)->applyFromArray([
                    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true]
                ]);

                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(25);
                $sheet->getRowDimension(5)->setRowHeight(70);
                $sheet->getRowDimension(6)->setRowHeight(90);
                $sheet->getRowDimension(7)->setRowHeight(90);
                $sheet->getRowDimension(8)->setRowHeight(25);

                for ($row = 9; $row <= $lastRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(30);
                }

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

                $sheet->getStyle('C9:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('C9:C' . $lastRow)->getAlignment()->setIndent(1);
            },
        ];
    }
}