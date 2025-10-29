<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GraduationSurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('graduation_survey')->truncate();
        DB::table('graduation_survey')->insert([
            ['survey_id' => 1, 'graduation_id' => 72],
            ['survey_id' => 2, 'graduation_id' => 78],
            ['survey_id' => 1, 'graduation_id' => 74],
            ['survey_id' => 1, 'graduation_id' => 75],
            ['survey_id' => 1, 'graduation_id' => 76],
            ['survey_id' => 3, 'graduation_id' => 81],
            ['survey_id' => 5, 'graduation_id' => 74]
        ]);
    }
}