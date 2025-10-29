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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('sso_id')->index();
            $table->string('full_name')->nullable();
            $table->string('code')->nullable();
            $table->text('access_token')->nullable();
            $table->string('remember_token')->nullable();
            $table->json('user_data')->nullable();
            $table->unsignedBigInteger('faculty_id')->nullable()->index();
            $table->string('status')->default('1');
            $table->string('type')->default('1');
            $table->timestamps();
            $table->unsignedBigInteger('role_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};