<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('major')->truncate();
        DB::table('major')->insert([
            [
                'id' => 1,
                'code' => '7480203',
                'name' => 'Kỹ thuật phần mềm',
                'description' => 'Ngành đào tạo về kỹ thuật phần mềm, lập trình, phát triển phần mềm',
                'status' => 1,
                'created_at' => '2025-07-15 09:36:56',
                'updated_at' => '2025-07-15 09:36:56',
                'deleted_at' => null
            ],
            [
                'id' => 2,
                'code' => '7480201',
                'name' => 'Công nghệ thông tin',
                'description' => 'Ngành đào tạo về công nghệ thông tin, lập trình, phát triển phần mềm',
                'status' => 1,
                'created_at' => '2025-07-15 09:36:56',
                'updated_at' => '2025-07-15 09:36:56',
                'deleted_at' => null
            ],
            [
                'id' => 3,
                'code' => '7480202',
                'name' => 'Mạng máy tính và truyền thông dữ liệu',
                'description' => 'Ngành đào tạo về mạng máy tính, truyền thông dữ liệu, an toàn thông tin',
                'status' => 1,
                'created_at' => '2025-07-15 09:36:56',
                'updated_at' => '2025-07-15 09:36:56',
                'deleted_at' => null
            ]
        ]);
    }
}