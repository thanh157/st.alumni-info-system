<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->truncate();
        DB::table('users')->insert([
            [
                'id' => 1,
                'sso_id' => '2',
                'full_name' => 'Can bo',
                'code' => 'CN01',
                'access_token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5ZWQ2NjkwOS1iNjNiLTRjZjctODliNC1mNjI2NDRmZmUxMzIiLCJqdGkiOiJmY2NhOTM5ZjA5ZjIwZjBkNWYyMzVlZmQzZTY4M2RkNDlkZGFiMTEwZDI4MzFiNTZkYzMyMTM4MTZiNmJiMGVlM2UzYTU2YjhiOWIzNmM0NCIsImlhdCI6MTc1NTk0MzU4OC4xNDUzNDksIm5iZiI6MTc1NTk0MzU4OC4xNDUzNTIsImV4cCI6MTc1NzIzOTU4OC4xMzgxMDcsInN1YiI6IjIiLCJzY29wZXMiOltdfQ.p5T9q8ws0d_G2WktpXZNPw-X0FIkNYnPAN5_chpKeYb73saxEPzG_JeSYjMksAVeBsrMi-WXoFe3dp_hiq1X8FIRhKN_Gt8t6qlmtoVeYgPL6cYUOw1e_RXWpvfMGi030wY6-xLlbw28ZpbQ8p_AbJ2M3ZeZ-_gw8vbibn1GNI5FYJn5ude65kITu2w9ozfVNsMc_xvIPVimlr5Ba9ITgx42Dw7t3uF4AYvNWkTaSf3T1NC2XXvKwXxzwZSq3wpU4uGZLonlBu7sQ_j7370ZVPlTdT9D4E1FEy6ilbOXAgRax6Xk6IGwGRuV3LDzynTVL4I-_xuJLtgwe1EiW8Jm9Mx89udLFZTxm0v3TjXT0B6L5rz32TsDw30gU9L7AECQGbhgmwUdyuzgjZMoCoaE9Kchbhc90d8yvoC01vmHfOnK4GRrvgyxOvNyHSD7MvCkktbfeOcA9OSkInEwB5iUdDKcc5LJwvvzD-vSPAI3r59K5399hVjUca_9m5ZNre6HC343x4kyK11jlEAC4hxGlFlM7Iz-yB6od_KZPlLef6mLcWIRedd6eCzxtpRlQvcSMA9SSnKfm9I-64qoCm4HnqsNFRqSnLX_ANtkgN_tCUns9sTMy2_Oc6wMIuP9wsPf4hv42sWN08jqJV9KaDIPBHSCOzqqM13ru3rPEAMGf2U',
                'remember_token' => null,
                'user_data' => '{"id": 2, "code": "CN01", "role": "officer", "email": "canbo@gmail.com", "phone": "", "status": "active", "full_name": "Can bo", "last_name": "Can", "user_name": "CN01", "created_at": "2025-05-05T02:37:01.000000Z", "faculty_id": 1, "first_name": "bo", "updated_at": "2025-07-07T08:00:01.000000Z", "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI5ZWQ2NjkwOS1iNjNiLTRjZjctODliNC1mNjI2NDRmZmUxMzIiLCJqdGkiOiJmY2NhOTM5ZjA5ZjIwZjBkNWYyMzVlZmQzZTY4M2RkNDlkZGFiMTEwZDI4MzFiNTZkYzMyMTM4MTZiNmJiMGVlM2UzYTU2YjhiOWIzNmM0NCIsImlhdCI6MTc1NTk0MzU4OC4xNDUzNDksIm5iZiI6MTc1NTk0MzU4OC4xNDUzNTIsImV4cCI6MTc1NzIzOTU4OC4xMzgxMDcsInN1YiI6IjIiLCJzY29wZXMiOltdfQ.p5T9q8ws0d_G2WktpXZNPw-X0FIkNYnPAN5_chpKeYb73saxEPzG_JeSYjMksAVeBsrMi-WXoFe3dp_hiq1X8FIRhKN_Gt8t6qlmtoVeYgPL6cYUOw1e_RXWpvfMGi030wY6-xLlbw28ZpbQ8p_AbJ2M3ZeZ-_gw8vbibn1GNI5FYJn5ude65kITu2w9ozfVNsMc_xvIPVimlr5Ba9ITgx42Dw7t3uF4AYvNWkTaSf3T1NC2XXvKwXxzwZSq3wpU4uGZLonlBu7sQ_j7370ZVPlTdT9D4E1FEy6ilbOXAgRax6Xk6IGwGRuV3LDzynTVL4I-_xuJLtgwe1EiW8Jm9Mx89udLFZTxm0v3TjXT0B6L5rz32TsDw30gU9L7AECQGbhgmwUdyuzgjZMoCoaE9Kchbhc90d8yvoC01vmHfOnK4GRrvgyxOvNyHSD7MvCkktbfeOcA9OSkInEwB5iUdDKcc5LJwvvzD-vSPAI3r59K5399hVjUca_9m5ZNre6HC343x4kyK11jlEAC4hxGlFlM7Iz-yB6od_KZPlLef6mLcWIRedd6eCzxtpRlQvcSMA9SSnKfm9I-64qoCm4HnqsNFRqSnLX_ANtkgN_tCUns9sTMy2_Oc6wMIuP9wsPf4hv42sWN08jqJV9KaDIPBHSCOzqqM13ru3rPEAMGf2U", "department_id": null, "is_only_login_ms": 0, "email_verified_at": null, "is_change_password": false}',
                'faculty_id' => 1,
                'status' => 'active',
                'type' => 'officer',
                'created_at' => '2025-07-15 09:23:52',
                'updated_at' => '2025-08-23 10:06:29',
                'role_id' => 1
            ],
            [
                'id' => 2,
                'sso_id' => 'ADMIN001',
                'full_name' => 'Quản trị viên hệ thống',
                'code' => 'ADMIN001',
                'access_token' => null,
                'remember_token' => null,
                'user_data' => '{"email": "admin@vnua.edu.vn", "department": "Phòng Công nghệ thông tin"}',
                'faculty_id' => null,
                'status' => 'active',
                'type' => 'admin',
                'created_at' => '2025-07-15 09:31:21',
                'updated_at' => '2025-07-15 09:31:21',
                'role_id' => 1
            ]
        ]);
    }
}