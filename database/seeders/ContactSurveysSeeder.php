<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactSurveysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contact_surveys')->truncate();
        DB::table('contact_surveys')->insert([
            [
                'id' => 1,
                'title' => 'PHIẾU KHẢO SÁT TTHU THẬP THÔNG TIN CỦA SINH VIÊN TỐT NGHIỆP NĂM 2024',
                'description' => 'Nhằm đánh giá hiệu quả của chương trình đào tạo và làm căn cứ để nâng cao chất lượng giáo dục, Học viện Nông nghiệp Việt Nam đang tiến hành khảo sát tình hình việc làm của sinh viên đã tốt nghiệp năm 2023, cũng như mức độ phù hợp giữa công việc hiện tại và chuyên ngành đã học.\r\n\r\nChúng tôi rất mong nhận được sự hợp tác từ Anh/Chị trong việc cung cấp thông tin một cách trung thực và đầy đủ. Tất cả các thông tin trong phiếu khảo sát sẽ được sử dụng chỉ cho mục đích nghiên cứu và đảm bảo bảo mật tuyệt đối.\r\n\r\nSự đóng góp của Anh/Chị có ý nghĩa rất lớn trong việc nâng cao chất lượng đào tạo của Học viện.',
                'start_time' => '2025-06-30 06:24:00',
                'end_time' => '2025-08-29 15:28:00',
                'status' => 1,
                'created_at' => '2025-07-15 15:25:21',
                'updated_at' => '2025-07-18 19:08:33'
            ],
            [
                'id' => 2,
                'title' => 'PHIẾU KHẢO SÁT TTHU THẬP THÔNG TIN CỦA SINH VIÊN TỐT NGHIỆP NĂM 2023',
                'description' => 'Nhằm đánh giá hiệu quả của chương trình đào tạo và làm căn cứ để nâng cao chất lượng giáo dục, Học viện Nông nghiệp Việt Nam đang tiến hành khảo sát tình hình việc làm của sinh viên đã tốt nghiệp năm 2023, cũng như mức độ phù hợp giữa công việc hiện tại và chuyên ngành đã học.\r\n\r\nChúng tôi rất mong nhận được sự hợp tác từ Anh/Chị trong việc cung cấp thông tin một cách trung thực và đầy đủ. Tất cả các thông tin trong phiếu khảo sát sẽ được sử dụng chỉ cho mục đích nghiên cứu và đảm bảo bảo mật tuyệt đối.\r\n\r\nSự đóng góp của Anh/Chị có ý nghĩa rất lớn trong việc nâng cao chất lượng đào tạo của Học viện.',
                'start_time' => '2025-07-24 11:46:00',
                'end_time' => '2025-08-07 12:46:00',
                'status' => 1,
                'created_at' => '2025-07-16 09:46:23',
                'updated_at' => '2025-07-18 19:08:10'
            ],
            [
                'id' => 3,
                'title' => 'PHIẾU KHẢO SÁT TTHU THẬP THÔNG TIN CỦA SINH VIÊN TỐT NGHIỆP NĂM 2022',
                'description' => 'Nhằm đánh giá hiệu quả của chương trình đào tạo và làm căn cứ để nâng cao chất lượng giáo dục, Học viện Nông nghiệp Việt Nam đang tiến hành khảo sát tình hình việc làm của sinh viên đã tốt nghiệp năm 2023, cũng như mức độ phù hợp giữa công việc hiện tại và chuyên ngành đã học.\r\n\r\nChúng tôi rất mong nhận được sự hợp tác từ Anh/Chị trong việc cung cấp thông tin một cách trung thực và đầy đủ. Tất cả các thông tin trong phiếu khảo sát sẽ được sử dụng chỉ cho mục đích nghiên cứu và đảm bảo bảo mật tuyệt đối.\r\n\r\nSự đóng góp của Anh/Chị có ý nghĩa rất lớn trong việc nâng cao chất lượng đào tạo của Học viện.',
                'start_time' => '2025-07-01 15:16:00',
                'end_time' => '2025-08-01 15:16:00',
                'status' => 1,
                'created_at' => '2025-07-16 15:16:36',
                'updated_at' => '2025-07-18 19:07:58'
            ]
        ]);
    }
}