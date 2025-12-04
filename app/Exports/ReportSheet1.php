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

class ReportSheet1 implements FromCollection, WithTitle, WithStyles, WithColumnWidths, WithEvents
{
    protected $schoolYear;
    protected $r1;
    protected $r1_trained_field;
    protected $r1_work_area;
    protected $r2;
    protected $r1Majors;

    public function __construct($schoolYear, $r1, $r1_trained_field, $r1_work_area, $r2, $r1Majors)
    {
        $this->schoolYear = $schoolYear;
        $this->r1 = $r1;
        $this->r1_trained_field = $r1_trained_field;
        $this->r1_work_area = $r1_work_area;
        $this->r2 = $r2;
        $this->r1Majors = $r1Majors;
    }

    public function collection()
    {
        $data = collect([
            // Row 1: Header 1
            ['HỌC VIỆN NÔNG NGHIỆP VIỆT NAM'],

            // Row 2: Header 2
            ['KHOA CÔNG NGHỆ THÔNG TIN'],

            // Row 3: Tiêu đề chính
            ['BÁO CÁO TÌNH HÌNH VIỆC LÀM CỦA SINH VIÊN TỐT NGHIỆP NĂM ' . $this->schoolYear],

            // Row 5: Dòng trống

            // Row 4: Dòng trống
            [''],

            // Row 5-8: Header table (4 rows)
            [
                'TT',
                'Mã ngành' . "\n" . '(Ghi theo mã ngành tuyển sinh theo thông tư số 24/2017/TT-BGDĐT. Khoa lấy thông tin mã ngành tại mẫu số 02)',
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
            // Row 8: Dòng số thứ tự (1), (2), (3)...
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
                '(15)',
                '(16)',
                '(17)',
                '(18)',
                '(19)'
            ],
        ]);

        // Thêm từng ngành
        $rowNumber = 1;
        foreach ($this->r1Majors as $major) {
            $currentMajorId = $major['training_industry_id'];

            $topCity = $this->r2->where('training_industry_id', $currentMajorId)
                ->map(function ($item) {
                    if (empty($item->recruit_partner_address)) return null;
                    $parts = explode(',', $item->recruit_partner_address);
                    $city = trim(end($parts));
                    return mb_convert_case($city, MB_CASE_TITLE, "UTF-8");
                })
                ->filter()
                ->unique()
                ->values()
                ->implode("\n");

            $data->push([
                $rowNumber++,
                $major['major_code'],
                $major['major_name'],
                $major['total_student'],
                $major['total_nu'],
                $major['total_res'],
                $major['total_res_nu'],
                $major['dung_nganh'],
                $major['lien_quan'],
                $major['khong_lien_quan'],
                $major['tiep_tuc_hoc'],
                $major['chua_co_viec'],
                $major['ty_le_co_viec_phan_hoi'] . '%',
                $major['ty_le_co_viec_tot_nghiep'] . '%',
                $major['nha_nuoc'],
                $major['tu_nhan'],
                $major['tu_tao'],
                $major['nuoc_ngoai'],
                $topCity ?? ''
            ]);
        }

        $majorsCollection = collect($this->r1Majors);

        $sumDungNganh = $majorsCollection->sum('dung_nganh');
        $sumLienQuan = $majorsCollection->sum('lien_quan');
        $sumKhongLienQuan = $majorsCollection->sum('khong_lien_quan');
        $sumTiepTucHoc = $majorsCollection->sum('tiep_tuc_hoc');
        $sumChuaCoViec = $majorsCollection->sum('chua_co_viec');

        $sumNhaNuoc = $majorsCollection->sum('nha_nuoc');
        $sumTuNhan = $majorsCollection->sum('tu_nhan');
        $sumTuTao = $majorsCollection->sum('tu_tao');
        $sumNuocNgoai = $majorsCollection->sum('nuoc_ngoai');

        $totalCoViecLam = $sumDungNganh + $sumLienQuan + $sumKhongLienQuan + $sumTiepTucHoc;

        $tyLeCoViecPhanHoi = $this->r1['total_res'] > 0
            ? round(($totalCoViecLam / $this->r1['total_res']) * 100, 2) . '%'
            : '0%';

        $tyLeCoViecTotNghiep = $this->r1['total_student'] > 0
            ? round(($totalCoViecLam / $this->r1['total_student']) * 100, 2) . '%'
            : '0%';

        $data->push([
            $rowNumber,
            '',
            'TỔNG HỢP',
            $this->r1['total_student'],
            $this->r1['total_nu'],
            $this->r1['total_res'],
            $this->r1['total_res_nu'],
            $sumDungNganh,
            $sumLienQuan,
            $sumKhongLienQuan,
            $sumTiepTucHoc,
            $sumChuaCoViec,
            $tyLeCoViecPhanHoi,
            $tyLeCoViecTotNghiep,
            $sumNhaNuoc,
            $sumTuNhan,
            $sumTuTao,
            $sumNuocNgoai,
            ''
        ]);

        return $data;
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
            'C' => 35,  // Tên ngành đào tạo
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

            // Header table - Rows 5-8 - CHỮ ĐEN, BỎ MÀU NỀN
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
                'font' => ['bold' => true, 'size' => 12, 'name' => 'Times New Roman'],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true
                ],
            ],
            8 => [
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

                // Apply Times New Roman to all cells
                $sheet->getStyle('A1:S' . $lastRow)
                    ->getFont()
                    ->setName('Times New Roman');

                // Merge cells cho header
                $sheet->mergeCells('A1:D1'); // HỌC VIỆN NÔNG NGHIỆP VIỆT NAM
                $sheet->mergeCells('A2:D2'); // KHOA CÔNG NGHỆ THÔNG TIN
                $sheet->mergeCells('A3:S3'); // Tiêu đề chính

                // Merge cells cho header table
                $sheet->mergeCells('A5:A7'); // TT
                $sheet->mergeCells('B5:B7'); // Mã ngành
                $sheet->mergeCells('C5:C7'); // Tên ngành đào tạo
                $sheet->mergeCells('D5:E6'); // Số sinh viên tốt nghiệp
                $sheet->mergeCells('F5:G6'); // Số sinh viên phản hồi
                $sheet->mergeCells('H5:L5'); // Tình hình việc làm
                $sheet->mergeCells('M5:M7'); // Tỷ lệ 1
                $sheet->mergeCells('N5:N7'); // Tỷ lệ 2
                $sheet->mergeCells('O5:R6'); // Khu vực làm việc
                $sheet->mergeCells('S5:S7'); // Nơi làm việc

                $sheet->mergeCells('H6:J6'); // Có việc làm
                $sheet->mergeCells('K6:K7'); // Tiếp tục học
                $sheet->mergeCells('L6:L7'); // Chưa có việc làm

                // Apply borders
                $sheet->getStyle('A5:S' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // Center alignment cho data rows
                $sheet->getStyle('A9:S' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A9:S' . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

                // Bold cho dòng TỔNG HỢP
                $sheet->getStyle('A' . $lastRow . ':S' . $lastRow)->getFont()->setBold(true);

                               // Set row height
                $sheet->getRowDimension(1)->setRowHeight(20);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(25);
                $sheet->getRowDimension(5)->setRowHeight(90);
                $sheet->getRowDimension(6)->setRowHeight(60);
                $sheet->getRowDimension(7)->setRowHeight(65);
                $sheet->getRowDimension(8)->setRowHeight(25);
                
                // Set row height cho data rows (từ row 9 đến row cuối - 1)
                // Dòng cuối cùng là TỔNG HỢP sẽ có chiều cao khác
                for ($row = 9; $row < $lastRow; $row++) {
                    $sheet->getRowDimension($row)->setRowHeight(250);
                }
                
                // Set chiều cao cho dòng TỔNG HỢP (dòng cuối cùng)
                $sheet->getRowDimension($lastRow)->setRowHeight(30);
                // BỎ MÀU ĐỎ - GIỮ MÀU ĐEN HẾT

                // Chữ ký
                $signatureRow = $lastRow + 4;
                $sheet->setCellValue('Q' . $signatureRow, 'Hà Nội, ngày     tháng     năm 2025');
                $sheet->mergeCells('Q' . $signatureRow . ':S' . $signatureRow);
                $sheet->getStyle('Q' . $signatureRow)->getFont()->setItalic(true)->setName('Times New Roman');
                $sheet->getStyle('Q' . $signatureRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('Q' . ($signatureRow + 1), 'TRƯỞNG KHOA');
                $sheet->mergeCells('Q' . ($signatureRow + 1) . ':S' . ($signatureRow + 1));
                $sheet->getStyle('Q' . ($signatureRow + 1))->getFont()->setBold(true)->setName('Times New Roman');
                $sheet->getStyle('Q' . ($signatureRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Wrap text cho cột nơi làm việc
                $sheet->getStyle('S9:S' . $lastRow)->getAlignment()->setWrapText(true);
            },
        ];
    }
}
