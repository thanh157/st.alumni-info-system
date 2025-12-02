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
        ]);

        // Thêm từng ngành
        $rowNumber = 1;
        foreach ($this->r1Majors as $major) {
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
                ''
            ]);
        }
        
        $majorsCollection = collect($this->r1Majors);

        $sumDungNganh      = $majorsCollection->sum('dung_nganh');
        $sumLienQuan       = $majorsCollection->sum('lien_quan');
        $sumKhongLienQuan  = $majorsCollection->sum('khong_lien_quan');
        $sumTiepTucHoc     = $majorsCollection->sum('tiep_tuc_hoc'); // Quan trọng: Lấy tổng tiếp tục học
        $sumChuaCoViec     = $majorsCollection->sum('chua_co_viec');
        
        $sumNhaNuoc        = $majorsCollection->sum('nha_nuoc');
        $sumTuNhan         = $majorsCollection->sum('tu_nhan');
        $sumTuTao          = $majorsCollection->sum('tu_tao');
        $sumNuocNgoai      = $majorsCollection->sum('nuoc_ngoai');

        // 2. Tính tổng có việc làm (Bao gồm cả tiếp tục học)
        $totalCoViecLam = $sumDungNganh + $sumLienQuan + $sumKhongLienQuan + $sumTiepTucHoc;

        // 3. Tính lại tỷ lệ %
        $tyLeCoViecPhanHoi = $this->r1['total_res'] > 0
            ? round(($totalCoViecLam / $this->r1['total_res']) * 100, 2) . '%'
            : '0%';

        $tyLeCoViecTotNghiep = $this->r1['total_student'] > 0
            ? round(($totalCoViecLam / $this->r1['total_student']) * 100, 2) . '%'
            : '0%';

        // 4. Push dòng tổng hợp vào Excel
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
            $sumTiepTucHoc,    // Đã khớp với logic tổng
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
            1 => [
                'font' => ['size' => 14, 'bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            2 => [
                'font' => ['size' => 14, 'bold' => true, 'underline' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
            4 => [
                'font' => ['size' => 15, 'bold' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
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
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow();

                // Merge cells cho header
                $sheet->mergeCells('A1:D1');
                $sheet->mergeCells('A2:D2');
                $sheet->mergeCells('A4:S4');

                // Merge cells cho header table
                $sheet->mergeCells('A6:A8');
                $sheet->mergeCells('B6:B8');
                $sheet->mergeCells('C6:C8');
                $sheet->mergeCells('D6:E7');
                $sheet->mergeCells('F6:G7');
                $sheet->mergeCells('H6:L6');
                $sheet->mergeCells('M6:M8');
                $sheet->mergeCells('N6:N8');
                $sheet->mergeCells('O6:R7');
                $sheet->mergeCells('S6:S8');

                $sheet->mergeCells('H7:J7');
                $sheet->mergeCells('K7:K8');
                $sheet->mergeCells('L7:L8');

                // Apply borders
                $sheet->getStyle('A6:S' . $lastRow)->applyFromArray([
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
                $sheet->getRowDimension(4)->setRowHeight(25);
                $sheet->getRowDimension(6)->setRowHeight(50);
                $sheet->getRowDimension(7)->setRowHeight(30);
                $sheet->getRowDimension(8)->setRowHeight(30);

                // Màu đỏ
                $sheet->getStyle('B6:B8')->getFont()->getColor()->setRGB('FF0000');
                $sheet->getStyle('S6:S8')->getFont()->getColor()->setRGB('FF0000');

                // Chữ ký
                $signatureRow = $lastRow + 4;
                $sheet->setCellValue('Q' . $signatureRow, 'Hà Nội, ngày     tháng     năm 2025');
                $sheet->mergeCells('Q' . $signatureRow . ':S' . $signatureRow);
                $sheet->getStyle('Q' . $signatureRow)->getFont()->setItalic(true);
                $sheet->getStyle('Q' . $signatureRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->setCellValue('Q' . ($signatureRow + 1), 'TRƯỞNG KHOA');
                $sheet->mergeCells('Q' . ($signatureRow + 1) . ':S' . ($signatureRow + 1));
                $sheet->getStyle('Q' . ($signatureRow + 1))->getFont()->setBold(true);
                $sheet->getStyle('Q' . ($signatureRow + 1))->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}