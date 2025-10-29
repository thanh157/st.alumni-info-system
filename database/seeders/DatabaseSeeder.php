<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            UsersSeeder::class,
            RolesSeeder::class,
            PermissionsSeeder::class,
            RolePermissionsSeeder::class,
            MajorSeeder::class,
            SurveySeeder::class,
            StudentSeeder::class,
            GraduationSeeder::class,
            GraduationStudentSeeder::class,
            GraduationSurveySeeder::class,
            ContactSurveysSeeder::class,
            ContactSurveyGraduationSeeder::class,
            AlumniContactSurveysSeeder::class,
            EmploymentSurveyResponsesV2Seeder::class,
        ]);

        Schema::enableForeignKeyConstraints();
    }
}