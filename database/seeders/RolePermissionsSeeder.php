<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role_permissions')->truncate();
        DB::table('role_permissions')->insert([
            ['id' => 1, 'role_id' => 1, 'permission_id' => 1, 'created_at' => null, 'updated_at' => null],
            ['id' => 2, 'role_id' => 1, 'permission_id' => 2, 'created_at' => null, 'updated_at' => null],
            ['id' => 3, 'role_id' => 1, 'permission_id' => 3, 'created_at' => null, 'updated_at' => null],
            ['id' => 4, 'role_id' => 1, 'permission_id' => 4, 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'role_id' => 1, 'permission_id' => 5, 'created_at' => null, 'updated_at' => null],
            ['id' => 6, 'role_id' => 1, 'permission_id' => 6, 'created_at' => null, 'updated_at' => null],
            ['id' => 7, 'role_id' => 1, 'permission_id' => 7, 'created_at' => null, 'updated_at' => null],
            ['id' => 8, 'role_id' => 1, 'permission_id' => 8, 'created_at' => null, 'updated_at' => null],
            ['id' => 9, 'role_id' => 1, 'permission_id' => 9, 'created_at' => null, 'updated_at' => null],
            ['id' => 10, 'role_id' => 1, 'permission_id' => 10, 'created_at' => null, 'updated_at' => null],
            ['id' => 11, 'role_id' => 1, 'permission_id' => 11, 'created_at' => null, 'updated_at' => null],
            ['id' => 12, 'role_id' => 1, 'permission_id' => 12, 'created_at' => null, 'updated_at' => null],
            ['id' => 13, 'role_id' => 1, 'permission_id' => 13, 'created_at' => null, 'updated_at' => null]
        ]);
    }
}