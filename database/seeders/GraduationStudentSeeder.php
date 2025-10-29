<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GraduationStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('graduation_student')->truncate();
        DB::table('graduation_student')->insert([
            ['graduation_id' => 72, 'student_id' => 410],
            ['graduation_id' => 73, 'student_id' => 59],
            ['graduation_id' => 73, 'student_id' => 69],
            ['graduation_id' => 73, 'student_id' => 164],
            ['graduation_id' => 81, 'student_id' => 510]
        ]);
    }
}