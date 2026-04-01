<?php

namespace App\Exports;

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
    protected $r1Majors;
    protected $type;
    protected $majors;
    protected $responsesByCode;

    public function __construct(
        $schoolYear,
        $r1,
        $r1_trained_field,
        $r1_work_area,
        $r2,
        $studentTab2,
        $alumniData,
        $r1Majors,
        $type = 'all'
    ) {
        $this->schoolYear = $schoolYear;
        $this->r1 = $r1;
        $this->r1_trained_field = $r1_trained_field;
        $this->r1_work_area = $r1_work_area;
        $this->r2 = $r2;
        $this->studentTab2 = $studentTab2;
        $this->alumniData = $alumniData;
        $this->r1Majors = $r1Majors;
        $this->type = $type;

        // Prepare data for tabs
        if (in_array($type, ['all', 'tab2', 'tab3'])) {
             $this->responsesByCode = $r2->keyBy('code_student');
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
                    $this->r2,
                    $this->r1Majors
                );
                break;

            case 'tab2':
                $sheets[] = new ReportSheet2(
                    $this->schoolYear,
                    $this->studentTab2,
                    $this->responsesByCode
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
                    $this->r2,
                    $this->r1Majors
                );
                $sheets[] = new ReportSheet2(
                    $this->schoolYear,
                    $this->studentTab2,
                    $this->responsesByCode
                );
                $sheets[] = new ReportSheet3(
                    $this->schoolYear,
                    $this->r2,
                    $this->majors
                );
                break;
        }

        return $sheets;
    }
}