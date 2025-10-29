<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SurveySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('survey')->truncate();
        DB::table('survey')->insert([
            [
                'id' => 1,
                'title' => 'PHIẾU KHẢO SÁT TỰ ĐÁNH GIÁ MỨC ĐỘ HÀI LÒNG VỀ VIỆC LÀM CỦA SINH VIÊN TỐT NGHIỆP NĂM 2023',
                'status' => 1,
                'description' => 'Nhằm đánh giá hiệu quả của chương trình đào tạo và làm căn cứ để nâng cao chất lượng giáo dục, Học viện Nông nghiệp Việt Nam đang tiến hành khảo sát tình hình việc làm của sinh viên đã tốt nghiệp năm 2023, cũng như mức độ phù hợp giữa công việc hiện tại và chuyên ngành đã học. Chúng tôi rất mong nhận được sự hợp tác từ Anh/Chị trong việc cung cấp thông tin một cách trung thực và đầy đủ. Tất cả các thông tin trong phiếu khảo sát sẽ được sử dụng chỉ cho mục đích nghiên cứu và đảm bảo bảo mật tuyệt đối. Sự đóng góp của Anh/Chị có ý nghĩa rất lớn trong việc nâng cao chất lượng đào tạo của Học viện.',
                'start_time' => '2025-07-03 16:50:00',
                'end_time' => '2025-08-06 22:50:00',
                'created_at' => '2025-07-15 09:50:22',
                'updated_at' => '2025-07-18 16:59:49',
                'deleted_at' => null
            ],
            [
                'id' => 2,
                'title' => 'PHIẾU KHẢO SÁT TỰ ĐÁNH GIÁ MỨC ĐỘ HÀI LÒNG VỀ VIỆC LÀM CỦA SINH VIÊN TỐT NGHIỆP NĂM 2024',
                'status' => 1,
                'description' => 'Nhằm đánh giá hiệu quả của chương trình đào tạo và làm căn cứ để nâng cao chất lượng giáo dục, Học viện Nông nghiệp Việt Nam đang tiến hành khảo sát tình hình việc làm của sinh viên đã tốt nghiệp năm 2023, cũng như mức độ phù hợp giữa công việc hiện tại và chuyên ngành đã học. Chúng tôi rất mong nhận được sự hợp tác từ Anh/Chị trong việc cung cấp thông tin một cách trung thực và đầy đủ. Tất cả các thông tin trong phiếu khảo sát sẽ được sử dụng chỉ cho mục đích nghiên cứu và đảm bảo bảo mật tuyệt đối. Sự đóng góp của Anh/Chị có ý nghĩa rất lớn trong việc nâng cao chất lượng đào tạo của Học viện.',
                'start_time' => '2025-07-16 14:25:00',
                'end_time' => '2025-07-22 14:22:00',
                'created_at' => '2025-07-17 07:22:58',
                'updated_at' => '2025-07-17 10:03:59',
                'deleted_at' => null
            ],
            [
                'id' => 3,
                'title' => 'PHIẾU KHẢO SÁT TỰ ĐÁNH GIÁ MỨC ĐỘ HÀI LÒNG VỀ VIỆC LÀM CỦA SINH VIÊN TỐT NGHIỆP NĂM 2022',
                'status' => 1,
                'description' => 'hằm đánh giá hiệu quả của chương trình đào tạo và làm căn cứ để nâng cao chất lượng giáo dục, Học viện Nông nghiệp Việt Nam đang tiến hành khảo sát tình hình việc làm của sinh viên đã tốt nghiệp năm 2023, cũng như mức độ phù hợp giữa công việc hiện tại và chuyên ngành đã học. Chúng tôi rất mong nhận được sự hợp tác từ Anh/Chị trong việc cung cấp thông tin một cách trung thực và đầy đủ. Tất cả các thông tin trong phiếu khảo sát sẽ được sử dụng chỉ cho mục đích nghiên cứu và đảm bảo bảo mật tuyệt đối. Sự đóng góp của Anh/Chị có ý nghĩa rất lớn trong việc nâng cao chất lượng đào tạo của Học viện.',
                'start_time' => '2025-07-17 19:04:00',
                'end_time' => '2025-08-15 17:04:00',
                'created_at' => '2025-07-17 10:04:51',
                'updated_at' => '2025-07-17 10:04:51',
                'deleted_at' => null
            ],
            [
                'id' => 4,
                'title' => 't',
                'status' => 1,
                'description' => 't',
                'start_time' => '2025-07-24 09:16:00',
                'end_time' => '2025-07-24 09:16:00',
                'created_at' => '2025-07-19 02:12:11',
                'updated_at' => '2025-07-19 02:12:37',
                'deleted_at' => '2025-07-19 02:12:37'
            ],
            [
                'id' => 5,
                'title' => 't',
                'status' => 1,
                'description' => 't',
                'start_time' => '2025-07-07 10:59:00',
                'end_time' => '2025-08-06 10:59:00',
                'created_at' => '2025-07-19 03:59:32',
                'updated_at' => '2025-07-19 03:59:32',
                'deleted_at' => null
            ]
        ]);
    }
}