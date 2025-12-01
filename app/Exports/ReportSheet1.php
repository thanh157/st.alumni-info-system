<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportSheet1 implements FromCollection, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $schoolYear;
    protected $r1;
    protected $r1_trained_field;
    protected $r1_work_area;
    protected $r2;

    public function __construct($schoolYear, $r1, $r1_trained_field, $r1_work_area, $r2)
    {
        $this->schoolYear = $schoolYear;
        $this->r1 = $r1;
        $this->r1_trained_field = $r1_trained_field;
        $this->r1_work_area = $r1_work_area;
        $this->r2 = $r2;
    }

    public function collection()
    {
        // Tính toán tỷ lệ
        $dungNganh = $this->r1_trained_field->dung_nganh ?? 0;
        $lienQuan = $this->r1_trained_field->lien_quan ?? 0;
        $khongLienQuan = $this->r1_trained_field->khong_lien_quan ?? 0;

        $totalCoViecLam = $dungNganh + $lienQuan + $khongLienQuan;

        // SỬA: Thêm ?? 0 để tránh lỗi
        $totalRes = $this->r1['total_res'] ?? 0;
        $totalStudent = $this->r1['total_student'] ?? 0;

        $tyLeCoViecPhanHoi = $totalRes > 0
            ? round(($totalCoViecLam / $totalRes) * 100, 2) . '%'
            : '0%';

        $tyLeCoViecTotNghiep = $totalStudent > 0
            ? round(($totalCoViecLam / $totalStudent) * 100, 2) . '%'
            : '0%';


        return collect([
            // Row 1: Header 1
            ['HỌC VIỆN NÔNG NGHIỆP VIỆT NAM'],

            // Row 2: Header 2
            ['KHOA CÔNG NGHỆ THÔNG TIN'],

            // Row 3: Dòng trống
            [''],

            // Row 4: Tiêu đề chính
            ['BÁO CÁO TÌNH HÌNH VIỆC LÀM CỦA SINH VIÊN TỐT NGHIỆP NĂM ' . $this->schoolYear],

            // Row 5: Dòng trống
            [''],

            // Row 6-8: Header table (3 rows)
            [
                'TT',
                'Mã ngành' . "\n" . '(Ghi theo mã ngành tuyển sinh theo thông tư số 24/2017/TT-BGDDT. Khoa lấy thông tin mã ngành tại mẫu số 02)',
                'Tên ngành đào tạo',
                'Số sinh viên tốt nghiệp',
                '',
                'Số sinh viên phản hồi',
                '',
                'Tình hình việc làm',
                '',
                '',
                '',
                '',
                'Tỷ lệ sinh viên có việc làm/ Tổng số sinh viên phản hồi',
                'Tỷ lệ sinh viên có việc làm/ Tổng số sinh viên tốt nghiệp',
                'Khu vực làm việc',
                '',
                '',
                '',
                'Nơi làm việc' . "\n" . '(Tỉnh/TP)' . "\n" . '(Tập hợp theo danh sách sinh viên phản hồi ở mẫu số 3)'
            ],
            [
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
                '',
                '',
                '',
                '',
                '',
                '',
                ''
            ],
            [
                '',
                '',
                '',
                'Tổng số',
                'Nữ',
                'Tổng số',
                'Nữ',
                'Đúng ngành đào tạo',
                'Liên quan đến ngành đào tạo',
                'Không liên quan đến ngành đào tạo',
                '',
                '',
                '',
                '',
                'Nhà nước',
                'Tư nhân',
                'Tự tạo việc làm',
                'Có yếu tố nước ngoài',
                ''
            ],
            // Row 11: Dữ liệu
            [
                '=ROW()-8', // thêm hàm đếm số thứ tự 
                '',
                '',
                $this->r1['total_student'],
                $this->r1['total_nu'],
                $this->r1['total_res'],
                $this->r1['total_res_nu'],
                $this->r1_trained_field->dung_nganh ?? 0,
                $this->r1_trained_field->lien_quan ?? 0,
                $this->r1_trained_field->khong_lien_quan ?? 0,
                $this->r2->where('employment_status', 2)->count(),
                $this->r2->where('employment_status', 3)->count(),
                $tyLeCoViecPhanHoi,
                $tyLeCoViecTotNghiep,
                $this->r1_work_area->nha_nuoc ?? 0,
                $this->r1_work_area->tu_nhan ?? 0,
                $this->r1_work_area->tu_tao ?? 0,
                $this->r1_work_area->nuoc_ngoai ?? 0,
                ''
            ],
        ]);
    }

    public function title(): string
    {
        return 'Mẫu báo cáo 1';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,   // TT
            'B' => 20,  // Mã ngành
            'C' => 25,  // Tên ngành đào tạo
            'D' => 10,  // Tổng số
            'E' => 10,  // Nữ
            'F' => 10,  // Tổng số
            'G' => 10,  // Nữ
            'H' => 12,  // Đúng ngành
            'I' => 15,  // Liên quan
            'J' => 15,  // Không liên quan
            'K' => 12,  // Tiếp tục học
            'L' => 12,  // Chưa có việc làm
            'M' => 15,  // Tỷ lệ 1
            'N' => 15,  // Tỷ lệ 2
            'O' => 12,  // Nhà nước
            'P' => 12,  // Tư nhân
            'Q' => 12,  // Tự tạo
            'R' => 12,  // Nước ngoài
            'S' => 20,  // Nơi làm việc
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Header 1 - Row 1
            1 => [
                'font' => ['size' => 14, 'bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],

            // Header 2 - Row 2
            2 => [
                'font' => ['size' => 14, 'bold' => true, 'underline' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],

            // Tiêu đề chính - Row 4
            4 => [
                'font' => ['size' => 15, 'bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],

            // Header table - Rows 6-9
            6 => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']],
            ],
            7 => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']],
            ],
            8 => [
                'font' => ['bold' => true, 'size' => 11],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E8E8E8']],
            ],
            9 => [
                'font' => ['bold' => false, 'size' => 10],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F5F5F5']],
            ],

            // Data row - Row 11
            11 => [
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Merge cells cho header
                $sheet->mergeCells('A1:D1'); // HỌC VIỆN NÔNG NGHIỆP VIỆT NAM
                $sheet->mergeCells('A2:D2'); // KHOA...
                $sheet->mergeCells('A4:S4'); // Tiêu đề chính

                // Merge cells cho header table
                // Row 6 (header level 1)
                $sheet->mergeCells('A6:A8'); // TT
                $sheet->mergeCells('B6:B8'); // Mã ngành
                $sheet->mergeCells('C6:C8'); // Tên ngành đào tạo
                $sheet->mergeCells('D6:E7'); // Số sinh viên tốt nghiệp
                $sheet->mergeCells('F6:G7'); // Số sinh viên phản hồi
                $sheet->mergeCells('H6:L6'); // Tình hình việc làm
                $sheet->mergeCells('M6:M8'); // Tỷ lệ 1
                $sheet->mergeCells('N6:N8'); // Tỷ lệ 2
                $sheet->mergeCells('O6:R7'); // Khu vực làm việc
                $sheet->mergeCells('S6:S8'); // Nơi làm việc

                // Row 7 (header level 2)
                $sheet->mergeCells('H7:J7'); // Có việc làm
                $sheet->mergeCells('K7:K8'); // Tiếp tục học
                $sheet->mergeCells('L7:L8'); // Chưa có việc làm

                // Apply borders cho toàn bộ bảng (từ row 6 đến row 11)
                $sheet->getStyle('A6:S11')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Set row height
                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(4)->setRowHeight(25);
                $sheet->getRowDimension(6)->setRowHeight(50);
                $sheet->getRowDimension(7)->setRowHeight(30);
                $sheet->getRowDimension(8)->setRowHeight(30);
                $sheet->getRowDimension(9)->setRowHeight(20);

                // Set màu đỏ cho text trong cột B (Mã ngành)
                $sheet->getStyle('B6:B8')->getFont()->getColor()->setRGB('FF0000');

                // Set màu đỏ cho text trong cột S (Nơi làm việc)
                $sheet->getStyle('S6:S8')->getFont()->getColor()->setRGB('FF0000');

                // Thêm chữ ký ở cuối (giống ảnh mẫu)
                $lastRow = 15; // Điều chỉnh vị trí chữ ký
                $sheet->setCellValue('Q' . $lastRow, 'Hà Nội, ngày     tháng     năm 2025');
                $sheet->mergeCells('Q' . $lastRow . ':S' . $lastRow);
                $sheet->getStyle('Q' . $lastRow)->getFont()->setItalic(true);
                $sheet->getStyle('Q' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('Q' . ($lastRow + 1), 'TRƯỞNG KHOA');
                $sheet->mergeCells('Q' . ($lastRow + 1) . ':S' . ($lastRow + 1));
                $sheet->getStyle('Q' . ($lastRow + 1))->getFont()->setBold(true);
                $sheet->getStyle('Q' . ($lastRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}
