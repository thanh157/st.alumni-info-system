<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('graduation_student', function (Blueprint $table) {
            $table->integer('graduation_id');
            $table->integer('student_id');
            
            // Không có primary key được định nghĩa trong SQL gốc,
            // nhưng bạn nên xem xét thêm:
            // $table->primary(['graduation_id', 'student_id']);
            // Hoặc thêm index để tăng tốc truy vấn:
            $table->index(['graduation_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_student');
    }
};