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

            // Row 3: Tiêu đề chính
            ['DANH SÁCH SINH VIÊN TỐT NGHIỆP NĂM ' . $this->schoolYear],

            // Row 4: Dòng trống
            [''],

            // Row 5-7: Header table (3 rows)
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
            // Row 7: Dòng số thứ tự (1), (2), (3)...
            [
                '(1)',
                '(2)',
                '(3)',
                '(4)',
                '(5)',
                '(6)',
                '(7)',
                '(8)',
                '(9)',
                '(10)',
                '(11)',
                '(12)',
                '(13)',
                '(14)',
                '(15)'
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
            // Header 1 - Row 1 - KHÔNG IN ĐẬM
            1 => [
                'font' => ['size' => 14, 'bold' => false, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],

            // Header 2 - Row 2 - IN ĐẬM, BỎ GẠCH CHÂN
            2 => [
                'font' => ['size' => 14, 'bold' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],

            // Tiêu đề chính - Row 3
            3 => [
                'font' => ['size' => 14, 'bold' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],

            // Header table - Rows 5-7 - CHỮ ĐEN, BỎ MÀU NỀN
            5 => [
                'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
            ],
            6 => [
                'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
            ],
            7 => [
                'font' => ['bold' => false, 'size' => 12, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // CHỈ set Times New Roman cho toàn bộ (KHÔNG set size ở đây)
                $sheet->getStyle('A1:O' . $lastRow)
                    ->getFont()
                    ->setName('Times New Roman');
                
                // Set size 11 CHỈ cho phần dữ liệu (từ row 8 trở đi)
                if ($lastRow >= 8) {
                    $sheet->getStyle('A8:O' . $lastRow)
                        ->getFont()
                        ->setSize(11);
                }

                // Merge cells cho header
                $sheet->mergeCells('A1:D1'); // HỌC VIỆN NÔNG NGHIỆP VIỆT NAM
                $sheet->mergeCells('A2:D2'); // KHOA CÔNG NGHỆ THÔNG TIN
                $sheet->mergeCells('A3:O3'); // Tiêu đề chính

                // Merge cells cho header table
                $sheet->mergeCells('A5:A6'); // TT
                $sheet->mergeCells('B5:B6'); // Mã sinh viên
                $sheet->mergeCells('C5:C6'); // Họ và tên
                $sheet->mergeCells('D5:D6'); // Nữ
                $sheet->mergeCells('E5:E6'); // Số thẻ CCCD
                $sheet->mergeCells('F5:F6'); // Mã ngành đào tạo
                $sheet->mergeCells('G5:H5'); // Quyết định tốt nghiệp
                $sheet->mergeCells('I5:J5'); // Thông tin liên hệ
                $sheet->mergeCells('K5:K6'); // Hình thức khảo sát
                $sheet->mergeCells('L5:L6'); // Có phản hồi
                $sheet->mergeCells('M5:M6'); // Ghi chú
                $sheet->mergeCells('N5:N6'); // Ngành
                $sheet->mergeCells('O5:O6'); // Khoa

                // Apply borders
                $sheet->getStyle('A5:O' . $lastRow)->applyFromArray([
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
                $sheet->getRowDimension(3)->setRowHeight(25);
                $sheet->getRowDimension(5)->setRowHeight(80);
                $sheet->getRowDimension(6)->setRowHeight(160);
                $sheet->getRowDimension(7)->setRowHeight(25);

                // Set chiều cao cho các dòng dữ liệu (từ row 8 trở đi)
                for ($row = 8; $row <= $lastRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(30);
                }

                // BỎ MÀU ĐỎ - GIỮ MÀU ĐEN HẾT

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