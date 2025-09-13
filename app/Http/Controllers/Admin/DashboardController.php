<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Graduation;
 use App\Models\SurveyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class DashboardController extends Controller
{

    //BE <-> API <-> JSON
    public function index()
    {
        //dd(SurveyResponse::all()->count());
        $totalResponses = SurveyResponse::count();
        $totalEmployed = SurveyResponse::where('employment_status',1)->count();
        $employmentRate = ($totalResponses > 0) ? round(($totalEmployed / $totalResponses) * 100) : 0;
        $totalGraduations = Graduation::count();
        $totalClasses = $this->getClassCountFromApi();

         return view('admin.pages.admin.dashboard', compact(
             'totalResponses',
             'employmentRate',
             'totalGraduations',
             'totalClasses'
         ));
    }

    private function getClassCountFromApi()
    {
    }

    private function getData(){

    }
}
