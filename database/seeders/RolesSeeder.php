<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->truncate();
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'Quản trị viên',
                'description' => 'Vai trò quản trị viên có đầy đủ tất cả các quyền trong hệ thống',
                'faculty_id' => 1,
                'created_at' => '2025-07-15 09:31:21',
                'updated_at' => '2025-07-15 09:31:21'
            ],
            [
                'id' => 2,
                'name' => 'Giảng viên',
                'description' => null,
                'faculty_id' => 1,
                'created_at' => '2025-07-17 09:28:33',
                'updated_at' => '2025-07-17 09:28:33'
            ]
        ]);
    }
}