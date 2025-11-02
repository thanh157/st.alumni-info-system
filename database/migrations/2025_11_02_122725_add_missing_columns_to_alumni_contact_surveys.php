<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('alumni_contact_surveys', function (Blueprint $table) {
            // Thông tin cá nhân bổ sung
            $table->string('ethnicity', 50)->nullable()->after('place_of_birth')->comment('Dân tộc');
            $table->string('nationality', 50)->default('Việt Nam')->after('ethnicity')->comment('Quốc tịch');

            // Thông tin học tập
            $table->string('class_name', 100)->nullable()->after('course')->comment('Tên lớp');
            $table->string('faculty_name', 255)->nullable()->after('class_name')->comment('Tên khoa');
            $table->string('major_name', 255)->nullable()->after('faculty_name')->comment('Ngành học');
            $table->string('training_system', 100)->nullable()->after('major_name')->comment('Hệ đào tạo');

            // Các bậc đã học tại Học viện
            $table->boolean('level_intermediate')->default(false)->after('training_system')->comment('Trung cấp');
            $table->boolean('level_college')->default(false)->after('level_intermediate')->comment('Cao đẳng');
            $table->boolean('level_bachelor')->default(true)->after('level_college')->comment('Đại học');
            $table->boolean('level_master')->default(false)->after('level_bachelor')->comment('Thạc sĩ');
            $table->boolean('level_phd')->default(false)->after('level_master')->comment('Tiến sĩ');

            // Tình trạng công việc
            $table->enum('employment_status', ['working', 'retired', 'other'])->default('working')->after('company_email')->comment('Tình trạng công việc');
            $table->string('position', 255)->nullable()->after('employment_status')->comment('Chức vụ hiện tại');
            $table->text('awards')->nullable()->after('position')->comment('Phần thưởng, giải thưởng');

            // Tình trạng kết nối
            $table->enum('connection_status', ['not_connected', 'connected'])->default('not_connected')->after('awards')->comment('Trạng thái kết nối');
            $table->string('connection_group', 255)->nullable()->after('connection_status')->comment('Nhóm kết nối (lớp, khóa, khoa)');

            // Indexes
            $table->index('student_code', 'idx_student_code');
            $table->index('survey_batch_id', 'idx_survey_batch');
            $table->index('employment_status', 'idx_employment_status');
            $table->index('connection_status', 'idx_connection_status');
        });
    }

    public function down()
    {
        Schema::table('alumni_contact_surveys', function (Blueprint $table) {
            $table->dropIndex('idx_student_code');
            $table->dropIndex('idx_survey_batch');
            $table->dropIndex('idx_employment_status');
            $table->dropIndex('idx_connection_status');

            $table->dropColumn([
                'ethnicity',
                'nationality',
                'class_name',
                'faculty_name',
                'major_name',
                'training_system',
                'level_intermediate',
                'level_college',
                'level_bachelor',
                'level_master',
                'level_phd',
                'employment_status',
                'position',
                'awards',
                'connection_status',
                'connection_group'
            ]);
        });
    }
};
