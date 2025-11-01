<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportSheet3 implements FromView, WithTitle, ShouldAutoSize
{
    protected $schoolYear, $r2, $majors;

    // Nhận 3 tham số (đã sửa ở ReportExport.php)
    public function __construct($schoolYear, $r2, $majors)
    {
        $this->schoolYear = $schoolYear;
        $this->r2 = $r2;
        $this->majors = $majors;
    }

    public function title(): string
    {
        return 'Mau bao cao 3'; // Tên của tab (sheet)
    }

    public function view(): View
    {
        return view('admin.pages.admin.exports.report_tab3', [
            'schoolYear' => $this->schoolYear,
            'r2' => $this->r2,
            'majors' => $this->majors,
        ]);
    }
}