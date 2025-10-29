<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSurveyGraduationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contact_survey_graduation')->truncate();
        DB::table('contact_survey_graduation')->insert([
            ['id' => 4, 'contact_survey_id' => 1, 'graduation_id' => 81],
            ['id' => 10, 'contact_survey_id' => 1, 'graduation_id' => 73],
            ['id' => 11, 'contact_survey_id' => 2, 'graduation_id' => 72],
            ['id' => 12, 'contact_survey_id' => 2, 'graduation_id' => 73],
            ['id' => 13, 'contact_survey_id' => 2, 'graduation_id' => 74],
            ['id' => 14, 'contact_survey_id' => 2, 'graduation_id' => 78],
            ['id' => 15, 'contact_survey_id' => 2, 'graduation_id' => 81],
            ['id' => 16, 'contact_survey_id' => 3, 'graduation_id' => 81],
            ['id' => 17, 'contact_survey_id' => 1, 'graduation_id' => 80]
        ]);
    }
}