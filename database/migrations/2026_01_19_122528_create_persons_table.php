<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('person_type', ['STUDENT', 'STAFF', 'VISITOR'])->default('STUDENT');
            $table->string('adm_or_staff_no')->unique()->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_names')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->date('dob')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->uuid('school_id')->nullable();
            $table->uuid('campus_id')->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE'])->default('ACTIVE');
            $table->timestamps();

            $table->index(['person_type', 'status']);
            $table->index('adm_or_staff_no');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
