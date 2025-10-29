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
        Schema::create('graduation_survey', function (Blueprint $table) {
            $table->integer('survey_id');
            $table->integer('graduation_id');
            
            // $table->primary(['survey_id', 'graduation_id']);
            $table->index(['survey_id', 'graduation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_survey');
    }
};