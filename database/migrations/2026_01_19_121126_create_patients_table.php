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
        Schema::create('patients', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Patient Type: 'student' or 'staff'
            $table->enum('type', ['student', 'staff'])->default('student');

            // Bio Data
            $table->string('first_name');
            $table->string('last_name');
            $table->string('student_id')->unique()->nullable(); // For students
            $table->string('staff_number')->unique()->nullable(); // For staff
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            // School Information (for students)
            $table->string('class')->nullable(); // e.g., "Form 2A", "Grade 5"
            $table->string('department')->nullable(); // For staff

            // Contact Information
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Guardian Information (for students)
            $table->string('guardian_name')->nullable();
            $table->string('guardian_phone')->nullable();
            $table->string('guardian_email')->nullable();
            $table->string('guardian_relationship')->nullable(); // e.g., "Parent", "Guardian"

            // Medical Information
            $table->text('allergies')->nullable();
            $table->text('chronic_conditions')->nullable();
            $table->text('medical_history')->nullable();
            $table->text('current_medications')->nullable();

            // Consent & Emergency
            $table->boolean('consent_first_aid')->default(false);
            $table->boolean('consent_emergency_care')->default(false);
            $table->date('consent_date')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['type', 'is_active']);
            $table->index('student_id');
            $table->index('staff_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};

/*
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Identity
            $table->string('person_type'); // STUDENT, STAFF, VISITOR
            $table->string('institutional_id')->nullable();

            // Dynamics integration
            $table->string('external_system')->nullable(); // DYNAMICS
            $table->string('external_id')->nullable();     // Dynamics GUID
            $table->timestamp('synced_at')->nullable();

            // Bio data (clinic-relevant only)
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender');
            $table->date('dob')->nullable();

            // Contacts
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            // Status
            $table->string('status')->default('ACTIVE');

            $table->timestamps();

            // Constraints
            $table->unique(['person_type', 'institutional_id']);
            $table->unique(['external_system', 'external_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persons');
    }
};
*/
