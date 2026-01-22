<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_enrolments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('person_id')->unique();
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->uuid('class_id')->nullable();
            $table->string('stream')->nullable();
            $table->enum('boarding_status', ['DAY', 'BOARDING'])->default('DAY');
            $table->uuid('guardian_primary_id')->nullable();
            $table->foreign('guardian_primary_id')->references('id')->on('guardians')->onDelete('set null');
            $table->timestamps();
            
            $table->index('class_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_enrolments');
    }
};
