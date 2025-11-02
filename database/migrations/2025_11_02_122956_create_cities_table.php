<?php
// File: database/migrations/2025_11_03_000002_create_cities_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique()->comment('Mã tỉnh/TP');
            $table->string('name', 255)->comment('Tên tỉnh/TP');
            $table->timestamps();

            $table->index('code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
};

