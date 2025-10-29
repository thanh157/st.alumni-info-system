<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumniContactSurveysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('alumni_contact_surveys')->truncate();
        DB::table('alumni_contact_surveys')->insert([
            [
                'id' => 1,
                'survey_batch_id' => 3,
                'student_code' => '621059',
                'full_name' => 'Nguyễn Thị Kim Tiến',
                'gender' => 'female',
                'date_of_birth' => '1998-06-14',
                'place_of_birth' => 'Tuyên Quang',
                'address' => 'TP Tuyên Quang',
                'phone' => '0972567267',
                'email' => '621059@sv.vnua.edu.vn',
                'facebook' => 'https://www.facebook.com/Lu157.003',
                'instagram' => 'https://www.instagram.com/lu.zxz?fbclid=IwY2xjawLmB31leHRuA2FlbQIxMABicmlkETF1WEhCT20xaFFUN3pyRHkyAR74QGBLUM3AQs0FEa7E9udPuYG1STXX-LkPwz2J_Ht08n9gmGAaXBW7Zaw5Cw_aem_YR_IWL9k6xQUqTagU1llUQ',
                'company_name' => 'FPT Software',
                'company_address' => 'Chùa Láng - Hà Nội',
                'company_phone' => '09876544',
                'company_email' => 'fpt@gmail.ocm',
                'created_at' => '2025-07-17 16:52:32',
                'updated_at' => '2025-07-17 17:34:32',
                'course' => 'Khóa: 62'
            ],
            [
                'id' => 2,
                'survey_batch_id' => 3,
                'student_code' => '621179',
                'full_name' => 'Trần Đình Cừ',
                'gender' => 'male',
                'date_of_birth' => '1999-07-22',
                'place_of_birth' => 'Hà Nội',
                'address' => 'Hà Nội',
                'phone' => '0911485808',
                'email' => '621179@sv.vnua.edu.vn',
                'facebook' => 'https://www.facebook.com/Lu157.003',
                'instagram' => 'https://www.instagram.com/lu.zxz?fbclid=IwY2xjawLmIt5leHRuA2FlbQIxMABicmlkETF1WEhCT20xaFFUN3pyRHkyAR7mBfAh9cqj3OS8JuDJE-J_PDfeFGSggn_slxOnN3AtLRiO1Ywr1fa4-I4wtg_aem_wmTAcIqnKMy6hfnglX45Pg',
                'company_name' => '1C Viet Nam',
                'company_address' => 'Tầng 21, Tòa nhà Century Tower, số 458 Minh Khai, Phường Vĩnh Tuy, Quận Hai Bà Trưng, Thành phố Hà Nội, Việt Nam',
                'company_phone' => '02471088887',
                'company_email' => 'support@1c.com.vn',
                'created_at' => '2025-07-17 18:42:36',
                'updated_at' => '2025-07-17 18:42:36',
                'course' => 'Khóa: 62'
            ],
              [
                'id' => 22,
                'survey_batch_id' => 1,
                'student_code' => '596538',
                'full_name' => 'Khuất Trung Hiếu',
                'gender' => 'male',
                'date_of_birth' => '1996-04-06',
                'place_of_birth' => 'Tuyên Quang',
                'address' => 'Tuyên Quang',
                'phone' => '0386515797',
                'email' => '596538@sv.vnua.edu.vn',
                'facebook' => 'https://www.facebook.com/Lu157.003',
                'instagram' => 'https://www.instagram.com/lu.zxz?fbclid=IwY2xjawLmB31leHRuA2FlbQIxMABicmlkETF1WEhCT20xaFFUN3pyRHkyAR74QGBLUM3AQs0FEa7E9udPuYG1STXX-LkPwz2J_Ht08n9gmGAaXBW7Zaw5Cw_aem_YR_IWL9k6xQUqTagU1llUQ',
                'company_name' => 'Tập đoàn Bưu chính Viễn thông Việt Nam – VNPT',
                'company_address' => 'Tầng 17, Tòa nhà CMC, số 11, phố Duy Tân, quận Cầu Giấy, Hà Nội',
                'company_phone' => '024 3795 8668',
                'company_email' => 'intel@gmail.com',
                'created_at' => '2025-07-19 01:34:20',
                'updated_at' => '2025-07-19 01:34:20',
                'course' => 'Khóa: 59'
            ]
        ]);
    }
}