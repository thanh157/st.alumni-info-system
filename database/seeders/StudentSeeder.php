<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('student')->truncate();
        DB::table('student')->insert([
            ['id' => 58, 'last_name' => 'Khuất Trung', 'first_name' => 'Hiếu', 'full_name' => 'Khuất Trung Hiếu', 'email' => '596538@sv.vnua.edu.vn', 'code' => '596538', 'training_industry_id' => null, 'school_year_end' => null, 'dob' => '1996-04-06', 'citizen_identification' => null, 'phone' => '0386515797', 'gender' => 'male', 'created_at' => '2025-06-22 17:40:44', 'updated_at' => '2025-06-22 17:40:44', 'deleted_at' => null],
            ['id' => 59, 'last_name' => 'Nguyễn Đình', 'first_name' => 'Quân', 'full_name' => 'Nguyễn Đình Quân', 'email' => '596606@sv.vnua.edu.vn', 'code' => '596606', 'training_industry_id' => null, 'school_year_end' => null, 'dob' => '1989-07-08', 'citizen_identification' => null, 'phone' => null, 'gender' => 'male', 'created_at' => '2025-06-22 17:40:44', 'updated_at' => '2025-06-22 17:40:44', 'deleted_at' => null],
            ['id' => 60, 'last_name' => 'An Thị', 'first_name' => 'Hường', 'full_name' => 'An Thị Hường', 'email' => '596556@sv.vnua.edu.vn', 'code' => '596556', 'training_industry_id' => null, 'school_year_end' => null, 'dob' => '1996-02-20', 'citizen_identification' => null, 'phone' => null, 'gender' => 'female', 'created_at' => '2025-06-22 17:40:44', 'updated_at' => '2025-06-22 17:40:44', 'deleted_at' => null],
            ['id' => 1366, 'last_name' => 'Ngô Xuân', 'first_name' => 'Sáng', 'full_name' => 'Ngô Xuân Sáng', 'email' => '655185@sv.vnua.edu.vn', 'code' => '655185', 'training_industry_id' => null, 'school_year_end' => null, 'dob' => '2002-04-01', 'citizen_identification' => null, 'phone' => '0946769390', 'gender' => 'male', 'created_at' => '2025-06-22 17:40:46', 'updated_at' => '2025-06-22 17:40:46', 'deleted_at' => null]
        ]);
    }
}