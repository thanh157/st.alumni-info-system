<?php

namespace App\Exports;
use App\Models\Major;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReportExport implements WithMultipleSheets
{
    protected $schoolYear;
    protected $r1;
    protected $r1_trained_field;
    protected $r1_work_area;
    protected $r2;
    protected $studentTab2;
    protected $alumniData;
    protected $type;
    protected $majors;
    protected $graduationData;
    protected $responsesByCode;


    public function __construct(
        $schoolYear,
        $r1,
        $r1_trained_field,
        $r1_work_area,
        $r2,
        $studentTab2,
        $alumniData,
        $type = 'all'
    ) {
        $this->schoolYear = $schoolYear;
        $this->r1 = $r1;
        $this->r1_trained_field = $r1_trained_field;
        $this->r1_work_area = $r1_work_area;
        $this->r2 = $r2;
        $this->studentTab2 = $studentTab2;
        $this->alumniData = $alumniData;
        $this->type = $type;


        // Lấy dữ liệu dùng chung cho Tab 2 và Tab 3
        // (Chỉ chạy khi cần export các tab này)
        if (in_array($type, ['all', 'tab2', 'tab3'])) {
            $this->majors = Major::all()->keyBy('id');
            // $r2 đã có sẵn từ $this->r2
            $this->responsesByCode = $r2->keyBy('code_student');
        }

        // Lấy dữ liệu dùng chung cho Tab 2
        // (Chỉ chạy khi cần export tab 2)
        if (in_array($type, ['all', 'tab2', 'tab3'])) {
            $this->majors = Major::all()->keyBy('id');
            $this->responsesByCode = $r2->keyBy('code_student');
        }

        if (in_array($type, ['all', 'tab2'])) {
            $studentIdsForGraduation = $studentTab2->pluck('id');
            $this->graduationData = \Illuminate\Support\Facades\DB::table('graduation_student')
                ->join('graduation', 'graduation_student.graduation_id', '=', 'graduation.id')
                ->whereIn('graduation_student.student_id', $studentIdsForGraduation)
                ->select('graduation_student.student_id', 'graduation.certification', 'graduation.certification_date')
                ->get()
                ->keyBy('student_id');
        }
     }

    public function sheets(): array
    {
        $sheets = [];

        switch ($this->type) {
            case 'tab1':
                $sheets[] = new ReportSheet1(
                    $this->schoolYear,
                    $this->r1,
                    $this->r1_trained_field,
                    $this->r1_work_area,
                    $this->r2
                );
                break;

            case 'tab2':
                // SỬA DÒNG NÀY: Truyền thêm 3 biến
                $sheets[] = new ReportSheet2(
                    $this->schoolYear,
                    $this->studentTab2,
                    $this->responsesByCode,
                    $this->graduationData,
                    $this->majors
                );
                break;

            case 'tab3':
                $sheets[] = new ReportSheet3(
                    $this->schoolYear,
                    $this->r2,
                    $this->majors
                );
                break;

            case 'tab4':
                $sheets[] = new ReportSheet4($this->alumniData);
                break;

            case 'all':
            default:
                $sheets[] = new ReportSheet1(
                    $this->schoolYear,
                    $this->r1,
                    $this->r1_trained_field,
                    $this->r1_work_area,
                    $this->r2
                );
                // SỬA DÒNG NÀY
                $sheets[] = new ReportSheet2(
                    $this->schoolYear,
                    $this->studentTab2,
                    $this->responsesByCode,
                    $this->graduationData,
                    $this->majors
                );
                // SỬA DÒNG NÀY
                $sheets[] = new ReportSheet3(
                    $this->schoolYear,
                    $this->r2,
                    $this->majors
                );
                $sheets[] = new ReportSheet4($this->alumniData);
                break;
        }

        return $sheets;
    }
}
