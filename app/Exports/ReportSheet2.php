<?php

namespace App\Exports;

use Carbon\Carbon;
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

use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;     

class ReportSheet2 implements FromCollection, WithTitle, WithStyles, WithColumnWidths, WithEvents, WithColumnFormatting
{
    protected $schoolYear;
    protected $studentTab2;
    protected $responsesByCode;

    public function __construct($schoolYear, $studentTab2, $responsesByCode)
    {
        $this->schoolYear = $schoolYear;
        $this->studentTab2 = $studentTab2;
        $this->responsesByCode = $responsesByCode;
    }

    public function collection()
    {
        $data = collect([
            // Row 1: Header 1
            ['HỌC VIỆN NÔNG NGHIỆP VIỆT NAM'],

            // Row 2: Header 2
            ['KHOA CÔNG NGHỆ THÔNG TIN'],

            // Row 3: Dòng trống
            [''],

            // Row 4: Tiêu đề chính
            ['DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM ' . $this->schoolYear],

            // Row 5: Dòng trống
            [''],

            // Row 6-7: Header table (2 rows)
            [
                'TT',
                'Mã sinh viên',
                'Họ và tên',
                'Nữ',
                'Số thẻ CCCD' . "\n" . '(Do Ban QLĐT, CTCT&CTSV cung cấp. Khoa bổ sung thông tin CCCD đối với sinh viên chưa có CCCD. Trường hợp CCCD của sinh viên bị sai, Khoa đính chính thông tin CCCD vào cột ghi chú)',
                'Mã ngành đào tạo',
                'Quyết định tốt nghiệp',
                '',
                'Thông tin liên hệ',
                '',
                'Hình thức khảo sát' . "\n" . '(Online, điện thoại, email, phỏng vấn, gửi tài liệu qua bưu điện...)',
                'Có phản hồi' . "\n" . '(Có phản hồi đánh dấu X)',
                'Ghi chú',
                'Ngành',
                'Khoa'
            ],
            [
                '',
                '',
                '',
                '',
                '',
                '',
                'Số Quyết định',
                'Ngày ký Quyết định',
                'Số điện thoại' . "\n" . '(Do Ban QLĐT, CTCT&CTSV cung cấp. Khoa bổ sung thông tin SĐT đối với sinh viên chưa có SĐT. Trường hợp SĐT của sinh viên bị sai, Khoa đính chính thông tin SĐT vào cột ghi chú)',
                'Email' . "\n" . '(KHÔNG điền thông tin email của sinh viên do HVN cấp)',
                '',
                '',
                '',
                '',
                ''
            ],
        ]);

        // Add student data - DÙNG DỮ LIỆU TỪ API
        foreach ($this->studentTab2 as $index => $student) {
            $studentCode = $student->code ?? '';
            
            // 1. Lấy response từ biến đã keyBy sẵn
            $response = $this->responsesByCode->get($studentCode);
            $hasResponse = !is_null($response);

            // 2. Logic CCCD: Ưu tiên API -> Fallback DB
            $cccd = $student->citizen_identification; 
            if (empty($cccd) && $hasResponse && !empty($response->identification_card_number)) {
                $cccd = $response->identification_card_number;
            }

            // --- KHẮC PHỤC LỖI E+11 TẠI ĐÂY ---
            // Thêm một khoảng trắng vào sau số CCCD để ép kiểu Text
            if (!empty($cccd)) {
                $cccd = $cccd . ' '; 
            }

            // 3. Logic SĐT, Email và Ghi chú
            $noteParts = [];
            
            // -- Phone --
            $phone = $student->phone ?? '';
            if ($hasResponse && !empty($response->phone_number)) {
                $phone = $response->phone_number;
                $noteParts[] = 'SĐT'; // Đánh dấu đã lấy từ khảo sát
            }
            if (!empty($phone)) {
                $phone = $phone . ' ';
            }

            // -- Email --
            $email = $student->email ?? '';
            if ($hasResponse && !empty($response->email)) {
                $email = $response->email;
                $noteParts[] = 'Email'; // Đánh dấu đã lấy từ khảo sát
            }

            // -- Note --
            $originalNote = $student->note ?? '';
            $addedNote = implode(', ', $noteParts);
            
            $finalNote = $originalNote;
            if (!empty($addedNote)) {
                $finalNote = !empty($finalNote) ? ($finalNote . ', ' . $addedNote) : $addedNote;
            }

            // 4. Format ngày
            $certDate = !empty($student->certification_date) 
                ? Carbon::parse($student->certification_date)->format('d/m/Y') 
                : '';

            $data->push([
                $index + 1,
                $studentCode,
                $student->full_name ?? '',
                ($student->gender ?? '') == 'female' ? 'X' : '',
                $cccd,                          // Biến đã xử lý
                $student->industry_code ?? '',
                $student->certification ?? '',
                $certDate,
                $phone,                         // Biến đã xử lý
                $email,                         // Biến đã xử lý
                'Online',
                $hasResponse ? 'X' : '',
                $finalNote,                     // Biến đã xử lý
                $student->industry_name ?? '',
                'Công nghệ thông tin',
            ]);
        }

        return $data;
    }

    public function title(): string
    {
        return 'Mẫu báo cáo 2';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // TT
            'B' => 12,  // Mã sinh viên
            'C' => 25,  // Họ và tên
            'D' => 6,   // Nữ
            'E' => 30,  // Số thẻ CCCD
            'F' => 15,  // Mã ngành đào tạo
            'G' => 18,  // Số Quyết định
            'H' => 15,  // Ngày ký Quyết định
            'I' => 30,  // Số điện thoại
            'J' => 30,  // Email
            'K' => 20,  // Hình thức khảo sát
            'L' => 12,  // Có phản hồi
            'M' => 15,  // Ghi chú
            'N' => 25,  // Ngành
            'O' => 20,  // Khoa
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['size' => 14, 'bold' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            2 => [
                'font' => ['size' => 14, 'bold' => true, 'underline' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            4 => [
                'font' => ['size' => 15, 'bold' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            6 => [
                'font' => ['bold' => true, 'size' => 11, 'name' => 'Times New Roman'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']],
            ],
            7 => [
                'font' => ['bold' => true, 'size' => 11, 'name' => 'Times New Roman'],
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

                // Apply Times New Roman to all cells
                $sheet->getStyle('A1:O' . $lastRow)
                    ->getFont()
                    ->setName('Times New Roman')
                    ->setSize(11);

                // Merge cells cho header
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A4:O4');

                // Merge cells cho header table
                $sheet->mergeCells('A6:A7');
                $sheet->mergeCells('B6:B7');
                $sheet->mergeCells('C6:C7');
                $sheet->mergeCells('D6:D7');
                $sheet->mergeCells('E6:E7');
                $sheet->mergeCells('F6:F7');
                $sheet->mergeCells('G6:H6');
                $sheet->mergeCells('I6:J6');
                $sheet->mergeCells('K6:K7');
                $sheet->mergeCells('L6:L7');
                $sheet->mergeCells('M6:M7');
                $sheet->mergeCells('N6:N7');
                $sheet->mergeCells('O6:O7');

                // Apply borders
                $sheet->getStyle('A6:O' . $lastRow)->applyFromArray([
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

                // Set row height
                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(4)->setRowHeight(25);
                $sheet->getRowDimension(6)->setRowHeight(60);
                $sheet->getRowDimension(7)->setRowHeight(60);

                // Màu đỏ
                $sheet->getStyle('E6:E7')->getFont()->getColor()->setRGB('FF0000');
                $sheet->getStyle('I6:J7')->getFont()->getColor()->setRGB('FF0000');

                // Chữ ký
                $signatureRow = $lastRow + 4;
                $richText = new RichText();
                $year = date('Y');

                $part1 = $richText->createTextRun("Hà Nội, ngày    tháng    năm {$year}\n\n");
                $part1->getFont()->setName('Times New Roman')->setItalic(true);

                $part2 = $richText->createTextRun("TRƯỞNG KHOA");
                $part2->getFont()->setBold(true)->setName('Times New Roman');

                $sheet->setCellValue('J' . $signatureRow, $richText);
                $sheet->mergeCells('J' . $signatureRow . ':L' . ($signatureRow + 4));

                $sheet->getStyle('J' . $signatureRow . ':L' . ($signatureRow + 4))
                    ->getAlignment()
                    ->setVertical(Alignment::VERTICAL_TOP)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setWrapText(true);
            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_TEXT, // Cột E: Số thẻ CCCD
            'I' => NumberFormat::FORMAT_TEXT, // Cột I: Số điện thoại (để tránh mất số 0 đầu hoặc bị E+)
        ];
    }
}