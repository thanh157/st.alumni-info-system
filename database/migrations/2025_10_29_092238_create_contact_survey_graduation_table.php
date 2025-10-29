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
        Schema::create('contact_survey_graduation', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_survey_id')->index();
            $table->unsignedBigInteger('graduation_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_survey_graduation');
    }
};