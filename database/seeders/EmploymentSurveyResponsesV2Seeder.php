<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploymentSurveyResponsesV2Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('employment_survey_responses_v2')->truncate();
        DB::table('employment_survey_responses_v2')->insert([
           
            [
                'id' => 112, 'survey_period_id' => 1, 'student_id' => 59, 'email' => '621179@sv.vnua.edu.vn', 'full_name' => 'Trần Đình Cừ', 'dob' => '1999-07-22', 'gender' => 'male', 'code_student' => '621179',
                'identification_card_number' => '087655443422', 'identification_card_number_update' => NULL, 'identification_issuance_place' => 'TP Hải Phòng', 'identification_issuance_date' => '2020-03-02', 'training_industry_id' => 3, 'course' => '62', 'phone_number' => '0911485808', 'employment_status' => 1,
                'recruit_partner_name' => 'Công ty Công nghệ thông tin Viettel', 'recruit_partner_address' => 'Cầu Giấy - Hà Nội', 'recruit_partner_date' => '2023-06-15', 'recruit_partner_position' => 'Tester', 'work_area' => '2', 'employed_since' => 1, 'trained_field' => 1, 'professional_qualification_field' => 2, 'level_knowledge_acquired' => 1, 'starting_salary' => 15, 'average_income' => 4,
                'recruitment_type' => '{"value":["2","3"],"content_other":null}', 'job_search_method' => '{"value":["2"],"content_other":null}', 'soft_skills_required' => '{"value":["2","4","5","6","7"],"content_other":null}', 'must_attended_courses' => '{"value":["2","3","4","5"],"content_other":null}', 'solutions_get_job' => '{"value":["3","4","6","7"],"content_other":"Tham quan các công ty"}',
                'created_at' => '2025-07-18 11:18:25', 'updated_at' => '2025-07-18 11:18:25', 'city_work_id' => NULL
            ],
            [
                'id' => 113, 'survey_period_id' => 1, 'student_id' => 59, 'email' => '622222@sv.vnua.edu.vn', 'full_name' => 'Lê Tuấn Tú', 'dob' => '1995-04-01', 'gender' => 'male', 'code_student' => '622222',
                'identification_card_number' => '087234544343', 'identification_card_number_update' => NULL, 'identification_issuance_place' => 'TP Hải Phòng', 'identification_issuance_date' => '2021-01-15', 'training_industry_id' => 2, 'course' => '62', 'phone_number' => '912817498', 'employment_status' => 1,
                'recruit_partner_name' => 'Công ty Công nghệ thông tin Viettel', 'recruit_partner_address' => 'Cầu Giấy - Hà Nội', 'recruit_partner_date' => '2022-10-20', 'recruit_partner_position' => 'BA', 'work_area' => '1', 'employed_since' => 4, 'trained_field' => 3, 'professional_qualification_field' => 1, 'level_knowledge_acquired' => 1, 'starting_salary' => 6, 'average_income' => 2,
                'recruitment_type' => '{"value":["3"],"content_other":null}', 'job_search_method' => '{"value":["1"],"content_other":null}', 'soft_skills_required' => '{"value":["2","3","4"],"content_other":null}', 'must_attended_courses' => '{"value":["3","4","5"],"content_other":null}', 'solutions_get_job' => '{"value":["3","4"],"content_other":null}',
                'created_at' => '2025-07-18 17:03:49', 'updated_at' => '2025-07-18 17:03:49', 'city_work_id' => NULL
            ],
            [
                'id' => 114, 'survey_period_id' => 1, 'student_id' => 59, 'email' => '621081@sv.vnua.edu.vn', 'full_name' => 'Trần Thị Lệ Thu', 'dob' => '2000-09-07', 'gender' => 'female', 'code_student' => '621081',
                'identification_card_number' => '08765544343', 'identification_card_number_update' => NULL, 'identification_issuance_place' => 'TP Hải Dương', 'identification_issuance_date' => '2021-02-15', 'training_industry_id' => 3, 'course' => '62', 'phone_number' => '0378458889', 'employment_status' => 1,
                'recruit_partner_name' => 'Công Ty Global CyberSoft Việt Nam', 'recruit_partner_address' => 'Hoàng Hoa Thám - Hà Nội', 'recruit_partner_date' => '2023-07-15', 'recruit_partner_position' => 'Designer', 'work_area' => '1', 'employed_since' => 1, 'trained_field' => 2, 'professional_qualification_field' => 2, 'level_knowledge_acquired' => 3, 'starting_salary' => 12, 'average_income' => 3,
                'recruitment_type' => '{"value":["2","3"],"content_other":null}', 'job_search_method' => '{"value":["1"],"content_other":null}', 'soft_skills_required' => '{"value":["2","3","5"],"content_other":null}', 'must_attended_courses' => '{"value":["2","3","4"],"content_other":null}', 'solutions_get_job' => '{"value":["3","4","5"],"content_other":null}',
                'created_at' => '2025-07-18 17:08:13', 'updated_at' => '2025-07-18 17:08:13', 'city_work_id' => NULL
            ],
            [
                'id' => 115, 'survey_period_id' => 2, 'student_id' => 59, 'email' => '647081@sv.vnua.edu.vn', 'full_name' => 'Hoàng Thị Thu Giang', 'dob' => '2001-06-03', 'gender' => 'female', 'code_student' => '647081',
                'identification_card_number' => '087631443422', 'identification_card_number_update' => NULL, 'identification_issuance_place' => 'TP Thái Bình', 'identification_issuance_date' => '2021-01-13', 'training_industry_id' => 1, 'course' => '64', 'phone_number' => '0347171329', 'employment_status' => 2,
                'recruit_partner_name' => 'Công ty cổ phần Bkav', 'recruit_partner_address' => 'Khu 2 Hoàng Khương, Thanh Ba, Phú Thọ', 'recruit_partner_date' => '2023-10-22', 'recruit_partner_position' => 'dev', 'work_area' => '3', 'employed_since' => 3, 'trained_field' => 1, 'professional_qualification_field' => 2, 'level_knowledge_acquired' => 3, 'starting_salary' => 8, 'average_income' => 4,
                'recruitment_type' => '{"value":["3"],"content_other":null}', 'job_search_method' => '{"value":["1"],"content_other":null}', 'soft_skills_required' => '{"value":["4"],"content_other":null}', 'must_attended_courses' => '{"value":["2"],"content_other":null}', 'solutions_get_job' => '{"value":["3"],"content_other":null}',
                'created_at' => '2025-07-18 17:36:35', 'updated_at' => '2025-07-18 17:36:35', 'city_work_id' => NULL
            ],
            [
                'id' => 116, 'survey_period_id' => 1, 'student_id' => 59, 'email' => '621057@sv.vnua.edu.vn', 'full_name' => 'Trần Thanh Bình', 'dob' => '1999-12-12', 'gender' => 'male', 'code_student' => '621057',
                'identification_card_number' => '087234544343', 'identification_card_number_update' => NULL, 'identification_issuance_place' => 'TP Hải Phòng', 'identification_issuance_date' => '2021-08-13', 'training_industry_id' => 3, 'course' => '62', 'phone_number' => '0332370135', 'employment_status' => 1,
                'recruit_partner_name' => 'Công Ty Global CyberSoft Việt Nam', 'recruit_partner_address' => 'Thanh Xuân - Hà Nội', 'recruit_partner_date' => '2025-03-16', 'recruit_partner_position' => 'Designer', 'work_area' => '3', 'employed_since' => 4, 'trained_field' => 3, 'professional_qualification_field' => 2, 'level_knowledge_acquired' => 1, 'starting_salary' => 5, 'average_income' => 3,
                'recruitment_type' => '{"value":["2","3"],"content_other":null}', 'job_search_method' => '{"value":["1"],"content_other":null}', 'soft_skills_required' => '{"value":["4"],"content_other":null}', 'must_attended_courses' => '{"value":["4"],"content_other":null}', 'solutions_get_job' => '{"value":["3"],"content_other":null}',
                'created_at' => '2025-07-18 22:55:13', 'updated_at' => '2025-07-18 22:55:13', 'city_work_id' => NULL
            ],
            [
                'id' => 117, 'survey_period_id' => 1, 'student_id' => 59, 'email' => '637708@sv.vnua.edu.vn', 'full_name' => 'Nguyễn Văn Canh', 'dob' => '2001-06-02', 'gender' => 'male', 'code_student' => '637708',
                'identification_card_number' => '087234544343', 'identification_card_number_update' => NULL, 'identification_issuance_place' => 'TP Thái Bình', 'identification_issuance_date' => '2022-03-12', 'training_industry_id' => 1, 'course' => '63', 'phone_number' => '385257472', 'employment_status' => 3,
                'recruit_partner_name' => 'Công ty cổ phần Bkav', 'recruit_partner_address' => 'Hoàng Hoa Thám - Hà Nội', 'recruit_partner_date' => '2025-04-14', 'recruit_partner_position' => 'Tester', 'work_area' => '4', 'employed_since' => 1, 'trained_field' => 3, 'professional_qualification_field' => 1, 'level_knowledge_acquired' => 3, 'starting_salary' => 8, 'average_income' => 4,
                'recruitment_type' => '{"value":["3","4"],"content_other":null}', 'job_search_method' => '{"value":["2"],"content_other":null}', 'soft_skills_required' => '{"value":["1","4","7"],"content_other":null}', 'must_attended_courses' => '{"value":["3"],"content_other":null}', 'solutions_get_job' => '{"value":["3"],"content_other":null}',
                'created_at' => '2025-07-18 22:59:03', 'updated_at' => '2025-07-18 22:59:03', 'city_work_id' => NULL
            ],
            [
                'id' => 118, 'survey_period_id' => 1, 'student_id' => 59, 'email' => '637736@sv.vnua.edu.vn', 'full_name' => 'Nguyễn Nam Khánh', 'dob' => '2000-07-11', 'gender' => 'male', 'code_student' => '637736',
                'identification_card_number' => '087655443422', 'identification_card_number_update' => NULL, 'identification_issuance_place' => 'TP Thái Bình', 'identification_issuance_date' => '2021-10-12', 'training_industry_id' => 1, 'course' => '63', 'phone_number' => '0987651217', 'employment_status' => 2,
                'recruit_partner_name' => 'chưa có', 'recruit_partner_address' => 'chưa có', 'recruit_partner_date' => '2022-10-10', 'recruit_partner_position' => 'chưa có', 'work_area' => '1', 'employed_since' => 2, 'trained_field' => 1, 'professional_qualification_field' => 1, 'level_knowledge_acquired' => 1, 'starting_salary' => 8, 'average_income' => 1,
                'recruitment_type' => '{"value":["4"],"content_other":null}', 'job_search_method' => '{"value":["2"],"content_other":null}', 'soft_skills_required' => '{"value":["4"],"content_other":null}', 'must_attended_courses' => '{"value":["4"],"content_other":null}', 'solutions_get_job' => '{"value":["5"],"content_other":null}',
                'created_at' => '2025-07-18 23:01:27', 'updated_at' => '2025-07-18 23:01:27', 'city_work_id' => NULL
            ],
            [
                'id' => 119, 'survey_period_id' => 1, 'student_id' => 59, 'email' => '637743@sv.vnua.edu.vn', 'full_name' => 'Đỗ Hữu Hải Long', 'dob' => '2001-10-09', 'gender' => 'male', 'code_student' => '637743',
                'identification_card_number' => '087655443422', 'identification_card_number_update' => NULL, 'identification_issuance_place' => 'TP Hải Dương', 'identification_issuance_date' => '2022-10-12', 'training_industry_id' => 3, 'course' => '63', 'phone_number' => '0852220900', 'employment_status' => 3,
                'recruit_partner_name' => 'chưa có', 'recruit_partner_address' => 'chưa có', 'recruit_partner_date' => '0001-01-01', 'recruit_partner_position' => 'chưa có', 'work_area' => '4', 'employed_since' => 1, 'trained_field' => 2, 'professional_qualification_field' => 2, 'level_knowledge_acquired' => 3, 'starting_salary' => 5, 'average_income' => 1,
                'recruitment_type' => '{"value":["1","3"],"content_other":null}', 'job_search_method' => '{"value":["1","4"],"content_other":null}', 'soft_skills_required' => '{"value":["4","6"],"content_other":null}', 'must_attended_courses' => '{"value":["5"],"content_other":null}', 'solutions_get_job' => '{"value":["1","2","3"],"content_other":null}',
                'created_at' => '2025-07-18 23:04:06', 'updated_at' => '2025-07-18 23:04:06', 'city_work_id' => NULL
            ],
            [
                'id' => 120, 'survey_period_id' => 3, 'student_id' => 59, 'email' => '621150@sv.vnua.edu.vn', 'full_name' => 'Đỗ Hữu Minh', 'dob' => '1999-10-28', 'gender' => 'male', 'code_student' => '621150',
                'identification_card_number' => '087655443422', 'identification_card_number_update' => NULL, 'identification_issuance_place' => 'Nghệ An', 'identification_issuance_date' => '2023-12-20', 'training_industry_id' => 2, 'course' => '62', 'phone_number' => '0362179224', 'employment_status' => 1,
                'recruit_partner_name' => 'Công ty TNHH Cốc Cốc', 'recruit_partner_address' => 'Tầng 8, tòa nhà đa năng ICON4, số 243A, đường Đê La Thành, phường Láng Thượng, quận Đống Đa, Hà Nội', 'recruit_partner_date' => '2025-04-15', 'recruit_partner_position' => 'Nhân viên kinh doanh', 'work_area' => '3', 'employed_since' => 2, 'trained_field' => 2, 'professional_qualification_field' => 1, 'level_knowledge_acquired' => 1, 'starting_salary' => 6, 'average_income' => 4,
                'recruitment_type' => '{"value":["2"],"content_other":null}', 'job_search_method' => '{"value":["2"],"content_other":null}', 'soft_skills_required' => '{"value":["4","6","7"],"content_other":null}', 'must_attended_courses' => '{"value":["1","2","3","4","5","6"],"content_other":null}', 'solutions_get_job' => '{"value":["3","4","5","6"],"content_other":null}',
                'created_at' => '2025-07-18 23:06:49', 'updated_at' => '2025-07-18 23:06:49', 'city_work_id' => NULL
            ],
            [
                'id' => 121, 'survey_period_id' => 3, 'student_id' => 59, 'email' => '621161@sv.vnua.edu.vn', 'full_name' => 'Bùi Thị Pháp', 'dob' => '1999-06-07', 'gender' => 'female', 'code_student' => '621161',
                'identification_card_number' => '08765544343', 'identification_card_number_update' => NULL, 'identification_issuance_place' => 'Hà Nội', 'identification_issuance_date' => '2021-12-22', 'training_industry_id' => 3, 'course' => '62', 'phone_number' => '1643052376', 'employment_status' => 2,
                'recruit_partner_name' => 'chưa có', 'recruit_partner_address' => 'chưa có', 'recruit_partner_date' => '0001-01-01', 'recruit_partner_position' => 'chưa có', 'work_area' => '4', 'employed_since' => 3, 'trained_field' => 2, 'professional_qualification_field' => 2, 'level_knowledge_acquired' => 3, 'starting_salary' => 5, 'average_income' => 2,
                'recruitment_type' => '{"value":["2","3"],"content_other":null}', 'job_search_method' => '{"value":["2"],"content_other":null}', 'soft_skills_required' => '{"value":["2","4","6"],"content_other":null}', 'must_attended_courses' => '{"value":["4","5","6"],"content_other":null}', 'solutions_get_job' => '{"value":["5","6"],"content_other":null}',
                'created_at' => '2025-07-18 23:11:24', 'updated_at' => '2025-07-18 23:13:23', 'city_work_id' => NULL
            ],
            [
                'id' => 122, 'survey_period_id' => 3, 'student_id' => 59, 'email' => '621093@sv.vnua.edu.vn', 'full_name' => 'Lưu Văn Hưng', 'dob' => '1999-08-30', 'gender' => 'male', 'code_student' => '621093',
                'identification_card_number' => '087631443422', 'identification_card_number_update' => NULL, 'identification_issuance_place' => 'Hà Nội', 'identification_issuance_date' => '2022-04-12', 'training_industry_id' => 2, 'course' => '62', 'phone_number' => '0964808737', 'employment_status' => 3,
                'recruit_partner_name' => 'Công ty Công nghệ thông tin Viettel', 'recruit_partner_address' => 'Khu 2 Hoàng Khương, Thanh Ba, Phú Thọ', 'recruit_partner_date' => '2024-03-25', 'recruit_partner_position' => 'BA', 'work_area' => '3', 'employed_since' => 2, 'trained_field' => 3, 'professional_qualification_field' => 1, 'level_knowledge_acquired' => 1, 'starting_salary' => 6, 'average_income' => 3,
                'recruitment_type' => '{"value":["1","2","3"],"content_other":null}', 'job_search_method' => '{"value":["1","4"],"content_other":null}', 'soft_skills_required' => '{"value":["1","2","3","4","5","6","7"],"content_other":null}', 'must_attended_courses' => '{"value":["3","4","5","6"],"content_other":null}', 'solutions_get_job' => '{"value":["3","4","5","6"],"content_other":null}',
                'created_at' => '2025-07-18 23:14:41', 'updated_at' => '2025-07-18 23:14:41', 'city_work_id' => NULL
            ],
            [
                'id' => 111,
                'survey_period_id' => 1,
                'student_id' => 59,
                'email' => '621059@sv.vnua.edu.vn',
                'full_name' => 'Nguyễn Thị Kim Tiến',
                'dob' => '1998-06-14',
                'gender' => 'female',
                'code_student' => '621059',
                'identification_card_number' => '08765544343',
                'identification_card_number_update' => null,
                'identification_issuance_place' => 'Tuyen quang',
                'identification_issuance_date' => '2021-04-12',
                'training_industry_id' => 3,
                'course' => 'Khóa: 62',
                'phone_number' => '0585125198',
                'employment_status' => 2,
                'recruit_partner_name' => 'Công ty Công nghệ thông tin Viettel',
                'recruit_partner_address' => 'Khu 2 Hoàng Khương, Thanh Ba, Phú Thọ',
                'recruit_partner_date' => '2024-03-10',
                'recruit_partner_position' => 'Tester',
                'work_area' => '3',
                'employed_since' => 3,
                'trained_field' => 1,
                'professional_qualification_field' => 1,
                'level_knowledge_acquired' => 1,
                'starting_salary' => 7,
                'average_income' => 2,
                'recruitment_type' => '{"value":["1","2","3"],"content_other":null}',
                'job_search_method' => '{"value":["1"],"content_other":null}',
                'soft_skills_required' => '{"value":["1","2","4","5"],"content_other":null}',
                'must_attended_courses' => '{"value":["3","4","6"],"content_other":null}',
                 'solutions_get_job' => '{"value":["5","6","7"],"content_other":"Gi\\\\u1edbi thi\\\\u1ec7u nhi\\\\u1ec1u c\\\\u00f4ng ty cho sinh vi\\\\u00ean h\\\\u01a1n"}',
                'created_at' => '2025-07-15 17:15:18',
                'updated_at' => '2025-07-18 13:04:06',
                'city_work_id' => null
            ],
             [
                'id' => 132,
                'survey_period_id' => 1,
                'student_id' => 59,
                'email' => '611208@sv.vnua.edu.vn',
                'full_name' => 'Nguyễn Thị Lan Anh',
                'dob' => '1999-08-08',
                'gender' => 'female',
                'code_student' => '611208',
                'identification_card_number' => '087655443422',
                'identification_card_number_update' => null,
                'identification_issuance_place' => 'Hà Nội',
                'identification_issuance_date' => '2023-03-12',
                'training_industry_id' => 3,
                'course' => '59',
                'phone_number' => '0349827676',
                'employment_status' => 2,
                'recruit_partner_name' => 'Công ty cổ phần Bkav',
                'recruit_partner_address' => 'Khu 2 Hoàng Khương, Thanh Ba, Phú Thọ',
                'recruit_partner_date' => '2022-12-12',
                'recruit_partner_position' => 'Designer',
                'work_area' => '3',
                'employed_since' => 1,
                'trained_field' => 1,
                'professional_qualification_field' => 2,
                'level_knowledge_acquired' => 1,
                'starting_salary' => 7,
                'average_income' => 4,
                'recruitment_type' => '{"value":["3","4"],"content_other":null}',
                'job_search_method' => '{"value":["1","2"],"content_other":null}',
                'soft_skills_required' => '{"value":["2","3","4"],"content_other":null}',
                'must_attended_courses' => '{"value":["2","3","4"],"content_other":null}',
                'solutions_get_job' => '{"value":["2","3"],"content_other":null}',
                'created_at' => '2025-07-19 03:09:19',
                'updated_at' => '2025-07-19 03:09:19',
                'city_work_id' => null
            ]
        ]);
    }
}