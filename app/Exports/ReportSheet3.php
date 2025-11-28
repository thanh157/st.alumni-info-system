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
            // Row 1: Header 1
            ['HỌC VIỆN NÔNG NGHIỆP VIỆT NAM'],

            // Row 2: Header 2
            ['KHOA CÔNG NGHỆ THÔNG TIN '],

            // Row 3: Dòng trống
            [''],

            // Row 4: Tiêu đề chính
            ['DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM ' . $this->schoolYear . ' PHẢN HỒI VỀ TÌNH HÌNH VIỆC LÀM'],

            // Row 5: Dòng trống
            [''],

            // Row 6: Header Level 1 (9 groups)
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
                ''
            ],

            // Row 7: Header Level 2
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
                'Giải pháp khác'
            ],

            // Row 8: Header Level 3
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
                'Từ 3 tháng đến dưới  6 tháng',
                'Từ  6 tháng  đến dưới 12 tháng',
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
                'HÌnh thức khác',
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

                // Tình hình việc làm
                $item->trained_field == 1 ? 'x' : '',
                $item->trained_field == 2 ? 'x' : '',
                $item->trained_field == 3 ? 'x' : '',
                $item->employment_status == 2 ? 'x' : '',
                $item->employment_status == 3 ? 'x' : '',

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
        // 78 cột từ A đến BZ
        return [
            'A' => 5,   // TT
            'B' => 12,  // Mã SV
            'C' => 20,  // Họ tên
            'D' => 12,  // Ngày sinh
            'E' => 10,  // Giới tính
            'F' => 15,  // CCCD
            'G' => 12,  // Mã ngành
            'H' => 12,  // Điện thoại
            'I' => 20,  // Email
            'J' => 12,  // Đúng ngành
            'K' => 12,  // Liên quan
            'L' => 12,  // Không liên quan
            'M' => 12,  // Tiếp tục học
            'N' => 12,  // Chưa có việc
            'O' => 10,  // Nhà nước
            'P' => 10,  // Tư nhân
            'Q' => 12,  // Tự tạo
            'R' => 12,  // Nước ngoài
            'S' => 15,  // Nơi làm việc
            'T' => 10,  // < 3 tháng
            'U' => 10,  // 3-6 tháng
            'V' => 10,  // 6-12 tháng
            'W' => 10,  // >= 12 tháng
            'X' => 12,  // Đã học được
            'Y' => 12,  // Một phần
            'Z' => 12,  // Không học được
            'AA' => 12, // Mức lương
            'AB' => 10, // < 5tr
            'AC' => 10, // 5-10tr
            'AD' => 10, // 10-15tr
            'AE' => 10, // >= 15tr
            'AF' => 12, // HV giới thiệu
            'AG' => 12, // Bạn bè
            'AH' => 12, // Tự tìm
            'AI' => 12, // Tự tạo
            'AJ' => 12, // Khác
            'AK' => 10, // Thi tuyển
            'AL' => 10, // Hợp đồng
            'AM' => 10, // Điều động
            'AN' => 10, // Xét tuyển
            'AO' => 10, // Biệt phái
            'AP' => 10, // Khác
            'AQ' => 10, // KN giao tiếp
            'AR' => 10, // KN thuyết trình
            'AS' => 10, // KN nhóm
            'AT' => 12, // KN viết BC
            'AU' => 10, // KN lãnh đạo
            'AV' => 10, // KN Tiếng Anh
            'AW' => 10, // KN Tin học
            'AX' => 10, // KN hội nhập
            'AY' => 10, // KN khác
            'AZ' => 12, // NC kiến thức
            'BA' => 12, // NC kỹ năng
            'BB' => 12, // NC CNTT
            'BC' => 12, // NC ngoại ngữ
            'BD' => 12, // Phát triển QL
            'BE' => 12, // Tiếp tục học
            'BF' => 15, // GP 1
            'BG' => 15, // GP 2
            'BH' => 15, // GP 3
            'BI' => 15, // GP 4
            'BJ' => 15, // GP 5
            'BK' => 12, // GP khác
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['size' => 14, 'bold' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [
                'font' => ['size' => 14, 'bold' => true, 'underline' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            4 => [
                'font' => ['size' => 15, 'bold' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            6 => [
                'font' => ['bold' => true, 'size' => 10, 'name' => 'Times New Roman'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']],
            ],
            7 => [
                'font' => ['bold' => true, 'size' => 10, 'name' => 'Times New Roman'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']],
            ],
            8 => [
                'font' => ['bold' => true, 'size' => 10, 'name' => 'Times New Roman'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Font Times New Roman
                // Apply Times New Roman to all cells
                $sheet->getStyle('A1:O' . $lastRow)
                    ->getFont()
                    ->setName('Times New Roman')
                    ->setSize(11);

                // Merge header
                $sheet->mergeCells('A1:F1');
                $sheet->mergeCells('A2:F2');
                $sheet->mergeCells('A4:P4');

                // Merge header table (Row 6-8)
                $sheet->mergeCells('A6:A8'); // TT
                $sheet->mergeCells('B6:B8'); // Mã SV
                $sheet->mergeCells('C6:C8'); // Họ tên
                $sheet->mergeCells('D6:D8'); // Ngày sinh
                $sheet->mergeCells('E6:E8'); // Giới tính
                $sheet->mergeCells('F6:F8'); // CCCD
                $sheet->mergeCells('G6:G8'); // Mã ngành
                $sheet->mergeCells('H6:H8'); // Điện thoại
                $sheet->mergeCells('I6:I8'); // Email

                // Tình hình việc làm
                $sheet->mergeCells('J6:N6');
                $sheet->mergeCells('J7:L7'); // Có việc làm
                $sheet->mergeCells('M7:M8'); // Tiếp tục học
                $sheet->mergeCells('N7:N8'); // Chưa có việc

                // Khu vực làm việc
                $sheet->mergeCells('O6:R7');

                $sheet->mergeCells('S6:S8'); // Nơi làm việc

                // Thời gian tìm việc
                $sheet->mergeCells('T6:W7');

                // Kiến thức kỹ năng
                $sheet->mergeCells('X6:Z7');

                $sheet->mergeCells('AA6:AA8'); // Mức lương

                // Thu nhập
                $sheet->mergeCells('AB6:AE7');

                // Hình thức tìm việc
                $sheet->mergeCells('AF6:AJ7');

                // Hình thức tuyển dụng
                $sheet->mergeCells('AK6:AP7');

                // Kỹ năng mềm
                $sheet->mergeCells('AQ6:AY7');

                // Khóa học
                $sheet->mergeCells('AZ6:BE7');

                // Giải pháp
                $sheet->mergeCells('BF6:BK7');

                // Borders
                $sheet->getStyle('A6:BK' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true
                    ]
                ]);

                // Row heights
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

                $sheet->getStyle('K' . $signatureRow)
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_TOP)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setWrapText(true);
            },
        ];
    }
}
