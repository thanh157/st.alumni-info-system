<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GraduationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('graduation')->truncate();
        DB::table('graduation')->insert([
            ['id' => 72, 'name' => 'Đợt xét tốt nghiệp tháng 05/2023', 'certification' => '123/QĐ-HVN', 'certification_date' => '2023-06-07', 'school_year' => 2023, 'student_count' => 1, 'faculty_id' => 1, 'created_at' => '2025-06-23 13:10:06', 'updated_at' => '2025-06-23 13:10:06', 'deleted_at' => null],
            ['id' => 73, 'name' => 'Đợt xét tốt nghiệp tháng 12/2022', 'certification' => '235/QĐ-HVN', 'certification_date' => '2023-01-15', 'school_year' => 2023, 'student_count' => 26, 'faculty_id' => 1, 'created_at' => '2025-06-23 13:10:06', 'updated_at' => '2025-06-23 13:10:06', 'deleted_at' => null],
            ['id' => 74, 'name' => 'Đợt xét tốt nghiệp tháng 03/2023', 'certification' => '1804/QĐ-HVN', 'certification_date' => '2023-03-30', 'school_year' => 2023, 'student_count' => 23, 'faculty_id' => 1, 'created_at' => '2025-06-23 13:10:06', 'updated_at' => '2025-06-23 13:10:06', 'deleted_at' => null],
            ['id' => 75, 'name' => 'Đợt xét tốt nghiệp tháng 07/2023', 'certification' => '4543/QĐ-HVN', 'certification_date' => '2023-08-14', 'school_year' => 2023, 'student_count' => 46, 'faculty_id' => 1, 'created_at' => '2025-06-23 13:10:06', 'updated_at' => '2025-06-23 13:10:06', 'deleted_at' => null],
            ['id' => 76, 'name' => 'Đợt xét tốt nghiệp tháng 09/2023', 'certification' => '5730/QĐ-HVN', 'certification_date' => '2023-10-16', 'school_year' => 2023, 'student_count' => 23, 'faculty_id' => 1, 'created_at' => '2025-06-23 13:10:06', 'updated_at' => '2025-06-23 13:10:06', 'deleted_at' => null],
            ['id' => 77, 'name' => 'Đợt xét tốt nghiệp tháng 12/2023', 'certification' => '295/QĐ-HVN', 'certification_date' => '2024-01-18', 'school_year' => 2024, 'student_count' => 14, 'faculty_id' => 1, 'created_at' => '2025-06-23 13:10:06', 'updated_at' => '2025-06-23 13:10:06', 'deleted_at' => null],
            ['id' => 78, 'name' => 'Đợt xét tốt nghiệp tháng 03/2024', 'certification' => '1766/QĐ-HVN', 'certification_date' => '2024-04-15', 'school_year' => 2024, 'student_count' => 44, 'faculty_id' => 1, 'created_at' => '2025-06-23 13:10:06', 'updated_at' => '2025-06-23 13:10:06', 'deleted_at' => null],
            ['id' => 79, 'name' => 'Đợt xét tốt nghiệp tháng 07/2024', 'certification' => '4079/QĐ-HVN', 'certification_date' => '2024-08-12', 'school_year' => 2024, 'student_count' => 66, 'faculty_id' => 1, 'created_at' => '2025-06-23 13:10:06', 'updated_at' => '2025-06-23 13:10:06', 'deleted_at' => null],
            ['id' => 80, 'name' => 'Đợt xét tốt nghiệp tháng 09/2024', 'certification' => '5646/QĐ-HVN', 'certification_date' => '2024-10-22', 'school_year' => 2024, 'student_count' => 26, 'faculty_id' => 1, 'created_at' => '2025-06-23 13:10:06', 'updated_at' => '2025-06-23 13:10:06', 'deleted_at' => null],
            ['id' => 81, 'name' => 'Đợt xét tốt nghiệp tháng 03-07-09-12/2022', 'certification' => 'xxxx/QĐ-HVN', 'certification_date' => '2022-12-30', 'school_year' => 2022, 'student_count' => 93, 'faculty_id' => 1, 'created_at' => '2025-06-23 13:10:06', 'updated_at' => '2025-06-23 13:10:06', 'deleted_at' => null]
        ]);
    }
}