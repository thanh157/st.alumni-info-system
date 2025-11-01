<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportSheet2 implements FromView, WithTitle, ShouldAutoSize
{
    protected $schoolYear, $studentTab2, $responsesByCode, $graduationData, $majors;

    // Nhận 5 tham số (đã sửa ở ReportExport.php)
    public function __construct($schoolYear, $studentTab2, $responsesByCode, $graduationData, $majors)
    {
        $this->schoolYear = $schoolYear;
        $this->studentTab2 = $studentTab2;
        $this->responsesByCode = $responsesByCode;
        $this->graduationData = $graduationData;
        $this->majors = $majors;
    }

    public function title(): string
    {
        return 'Mau bao cao 2'; 
    }

    public function view(): View
    {
        return view('admin.pages.admin.exports.report_tab2', [
            'schoolYear' => $this->schoolYear,
            'studentTab2' => $this->studentTab2,
            'responsesByCode' => $this->responsesByCode,
            'graduationData' => $this->graduationData,
            'majors' => $this->majors,
        ]);
    }
}