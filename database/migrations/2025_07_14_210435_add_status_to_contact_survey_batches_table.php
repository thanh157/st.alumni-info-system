<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('contact_survey_batches', function (Blueprint $table) {
            $table->boolean('status')->default(1); // 1: hoạt động, 0: ẩn
        });
    }

    public function down()
    {
        Schema::table('contact_survey_batches', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

};
