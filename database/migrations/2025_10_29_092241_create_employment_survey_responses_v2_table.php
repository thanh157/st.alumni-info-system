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
        Schema::create('employment_survey_responses_v2', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('survey_period_id')->index();
            $table->unsignedBigInteger('student_id')->nullable()->index();
            $table->string('email')->nullable();
            $table->string('full_name')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('code_student')->nullable();
            $table->string('identification_card_number', 100)->nullable();
            $table->string('identification_card_number_update', 30)->nullable();
            $table->string('identification_issuance_place', 100)->nullable();
            $table->date('identification_issuance_date')->nullable();
            $table->unsignedBigInteger('training_industry_id')->nullable()->index();
            $table->string('course')->nullable();
            $table->string('phone_number', 20)->nullable();
            $table->integer('employment_status')->unsigned()->nullable();
            $table->string('recruit_partner_name')->nullable();
            $table->string('recruit_partner_address')->nullable();
            $table->date('recruit_partner_date')->nullable();
            $table->string('recruit_partner_position')->nullable();
            $table->string('work_area')->nullable();
            $table->integer('employed_since')->unsigned()->nullable();
            $table->integer('trained_field')->unsigned()->nullable();
            $table->integer('professional_qualification_field')->unsigned()->nullable();
            $table->integer('level_knowledge_acquired')->unsigned()->nullable();
            $table->bigInteger('starting_salary')->unsigned()->nullable();
            $table->integer('average_income')->unsigned()->nullable();
            $table->longText('recruitment_type')->nullable();
            $table->longText('job_search_method')->nullable();
            $table->longText('soft_skills_required')->nullable();
            $table->longText('must_attended_courses')->nullable();
            $table->longText('solutions_get_job')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('city_work_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_survey_responses_v2');
    }
};