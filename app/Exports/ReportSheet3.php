<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\RichText\RichText;

class ReportSheet3 implements FromView, WithTitle, ShouldAutoSize, WithEvents
{
    protected $schoolYear, $r2, $majors;

    public function __construct($schoolYear, $r2, $majors)
    {
        $this->schoolYear = $schoolYear;
        $this->r2 = $r2;
        $this->majors = $majors;
    }

    public function title(): string
    {
        return 'Mau bao cao 3';
    }

    public function view(): View
    {
        return view('admin.pages.admin.exports.report_tab3', [
            'schoolYear' => $this->schoolYear,
            'r2' => $this->r2,
            'majors' => $this->majors,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Lấy dòng + cột cuối
                $lastRow = $sheet->getHighestRow();
                $lastColumn = $sheet->getHighestColumn();

                // 1. Font Times New Roman cho toàn sheet
                $sheet->getStyle("A1:{$lastColumn}{$lastRow}")
                    ->getFont()
                    ->setName('Times New Roman')
                    ->setSize(11);

                // 2. Chỉ áp dụng viền bảng từ A5 đến hết dữ liệu
                $startRow = 5; // Dòng bắt đầu viền
                $range = "A{$startRow}:{$lastColumn}{$lastRow}";

                $sheet->getStyle($range)
                    ->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ]
                        ],
                        'alignment' => [
                            'horizontal' => 'center',
                            'vertical' => 'center',
                            'wrapText' => true,
                        ]
                    ]);

                // Giả sử dữ liệu của bạn đã export, $lastRow là dòng cuối cùng có dữ liệu
                $lastRow = $sheet->getHighestRow() + 4; 

                $richText = new RichText();
                $year = date('Y');

                // Dòng ngày tháng
                $part1 = $richText->createTextRun("Hà Nội, ngày    tháng    năm {$year}\n\n");
                $part1->getFont()->setName('Times New Roman');

                // Dòng TRƯỞNG KHOA in đậm
                $part2 = $richText->createTextRun("TRƯỞNG KHOA");
                $part2->getFont()->setBold(true);
                $part2->getFont()->setName('Times New Roman');

                // Gán RichText vào ô cuối
                $sheet->setCellValue("K{$lastRow}", $richText);

                // Merge vùng ký tên, ví dụ 8 cột: K->R và 5 dòng: lastRow->lastRow+4
                $sheet->mergeCells("K{$lastRow}:R" . ($lastRow + 4));

                // Căn top-left và wrap text
                $sheet->getStyle("K{$lastRow}:R" . ($lastRow + 4))->getAlignment()
                    ->setVertical(Alignment::VERTICAL_TOP)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setWrapText(true);
            }
        ];
    }
}
