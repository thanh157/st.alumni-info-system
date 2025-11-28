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

class ReportSheet2 implements FromCollection, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $schoolYear;
    protected $studentTab2;
    protected $responsesByCode;
    protected $graduationData;
    protected $majors;

    public function __construct($schoolYear, $studentTab2, $responsesByCode, $graduationData, $majors)
    {
        $this->schoolYear = $schoolYear;
        $this->studentTab2 = $studentTab2;
        $this->responsesByCode = $responsesByCode;
        $this->graduationData = $graduationData;
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
                'Có phản hồi' . "\n" . '(Có phản hồi đánh dấu x)',
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

        // Add student data
        foreach ($this->studentTab2 as $index => $student) {
            $graduation = $this->graduationData->get($student->id);
            $response = $this->responsesByCode->get($student->code);
            $major = $this->majors->get($student->training_industry_id);

            $data->push([
                $index + 1,
                $student->code,
                $student->full_name,
                $student->gender == 'female' ? 'x' : '',
                $student->citizen_identification ?? '',
                optional($major)->code ?? '',
                optional($graduation)->certification ?? '',
                optional($graduation)->certification_date ? date('d-m-Y', strtotime($graduation->certification_date)) : '',
                $student->phone ?? '',
                $student->email ?? '',
                '', // Hình thức khảo sát
                $response ? 'Có' : 'Không',
                '', // Ghi chú
                optional($major)->name ?? '',
                '' // Khoa
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
            // Header 1 - Row 1
            1 => [
                'font' => ['size' => 14, 'bold' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],

            // Header 2 - Row 2
            2 => [
                'font' => ['size' => 14, 'bold' => true, 'underline' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],

            // Tiêu đề chính - Row 4
            4 => [
                'font' => ['size' => 15, 'bold' => true, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],

            // Header table - Rows 6-8
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
            8 => [
                'font' => ['bold' => false, 'size' => 10, 'name' => 'Times New Roman'],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F5F5']],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Get last row
                $lastRow = $sheet->getHighestRow();

                // Apply Times New Roman to all cells
                $sheet->getStyle('A1:O' . $lastRow)
                    ->getFont()
                    ->setName('Times New Roman')
                    ->setSize(11);

                // Merge cells cho header
                $sheet->mergeCells('A1:D1'); // HỌC VIỆN NÔNG NGHIỆP VIỆT NAM
                $sheet->mergeCells('A2:D2'); // KHOA...
                $sheet->mergeCells('A4:O4'); // Tiêu đề chính

                // Merge cells cho header table
                // Row 6-7 (header level 1 & 2)
                $sheet->mergeCells('A6:A7'); // TT
                $sheet->mergeCells('B6:B7'); // Mã sinh viên
                $sheet->mergeCells('C6:C7'); // Họ và tên
                $sheet->mergeCells('D6:D7'); // Nữ
                $sheet->mergeCells('E6:E7'); // Số thẻ CCCD
                $sheet->mergeCells('F6:F7'); // Mã ngành đào tạo
                $sheet->mergeCells('G6:H6'); // Quyết định tốt nghiệp
                $sheet->mergeCells('I6:J6'); // Thông tin liên hệ
                $sheet->mergeCells('K6:K7'); // Hình thức khảo sát
                $sheet->mergeCells('L6:L7'); // Có phản hồi
                $sheet->mergeCells('M6:M7'); // Ghi chú
                $sheet->mergeCells('N6:N7'); // Ngành
                $sheet->mergeCells('O6:O7'); // Khoa

                // Apply borders cho toàn bộ bảng (từ row 6 đến end)
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
                $sheet->getRowDimension(8)->setRowHeight(20);

                // Set màu đỏ cho text trong một số cột
                $sheet->getStyle('E6:E7')->getFont()->getColor()->setRGB('FF0000');
                $sheet->getStyle('I6:J7')->getFont()->getColor()->setRGB('FF0000');

                // Thêm chữ ký ở cuối
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
}
