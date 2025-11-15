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
        Schema::table('survey', function (Blueprint $table) {
            if (!Schema::hasColumn('survey', 'total_graduations')) {
                $table->unsignedInteger('total_graduations')->default(0)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey', function (Blueprint $table) {
            if (Schema::hasColumn('survey', 'total_graduations')) {
                $table->dropColumn('total_graduations');
            }
        });
    }
};
