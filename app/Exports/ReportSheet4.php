<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportSheet4 implements FromView, WithTitle, ShouldAutoSize
{
    protected $alumniData;

    public function __construct($alumniData)
    {
        $this->alumniData = $alumniData;
    }

    public function title(): string
    {
        return 'Thong tin Cuu sinh vien'; // Tên của tab (sheet)
    }

    public function view(): View
    {
        return view('admin.pages.admin.exports.report_tab4', [
            'alumniData' => $this->alumniData,
        ]);
    }
}