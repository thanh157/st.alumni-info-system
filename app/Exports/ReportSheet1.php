<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportSheet1 implements FromView, WithTitle, ShouldAutoSize
{
    protected $schoolYear, $r1, $r1_trained_field, $r1_work_area, $r2;

    public function __construct($schoolYear, $r1, $r1_trained_field, $r1_work_area, $r2)
    {
        $this->schoolYear = $schoolYear;
        $this->r1 = $r1;
        $this->r1_trained_field = $r1_trained_field;
        $this->r1_work_area = $r1_work_area;
        $this->r2 = $r2;
    }

    public function title(): string
    {
        return 'Mau bao cao 1'; // Tên của tab (sheet)
    }

    public function view(): View
    {
        return view('admin.pages.admin.exports.report_tab1', [
            'schoolYear' => $this->schoolYear,
            'r1' => $this->r1,
            'r1_trained_field' => $this->r1_trained_field,
            'r1_work_area' => $this->r1_work_area,
            'r2' => $this->r2,
        ]);
    }
}