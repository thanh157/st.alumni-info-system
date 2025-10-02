<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportExport implements FromView, WithTitle, ShouldAutoSize
{
    // Khai báo các thuộc tính để lưu trữ dữ liệu
    protected $report1, $students, $report3, $schoolYear, $r1, $r1_trained_field, $r1_work_area, $r2, $studentTab2;

    /**
     * Hàm khởi tạo để nhận tất cả dữ liệu cần thiết từ Controller.
     */
    public function __construct($report1, $students, $report3, $schoolYear, $r1, $r1_trained_field, $r1_work_area, $r2, $studentTab2)
    {
        $this->report1 = $report1;
        $this->students = $students;
        $this->report3 = $report3;
        $this->schoolYear = $schoolYear;
        $this->r1 = $r1;
        $this->r1_trained_field = $r1_trained_field;
        $this->r1_work_area = $r1_work_area;
        $this->r2 = $r2;
        $this->studentTab2 = $studentTab2;
    }

    /**
     * Trả về view sẽ được dùng để render ra file Excel.
     */
    public function view(): View
    {
        // Truyền tất cả dữ liệu đã nhận vào view template
        return view('admin.pages.admin.exports.report_excel', [
            'report1' => $this->report1,
            'students' => $this->students,
            'report3' => $this->report3,
            'schoolYear' => $this->schoolYear,
            'r1' => $this->r1,
            'r1_trained_field' => $this->r1_trained_field,
            'r1_work_area' => $this->r1_work_area,
            'r2' => $this->r2,
            'studentTab2' => $this->studentTab2,
        ]);
    }

    /**
     * Đặt tên cho sheet trong file Excel.
     */
    public function title(): string
    {
        return 'Bao_cao_tong_hop_viec_lam';
    }
}

