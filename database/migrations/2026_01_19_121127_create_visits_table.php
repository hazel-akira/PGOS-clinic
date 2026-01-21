<?php

#use Illuminate\Database\Migrations\Migration;
#use Illuminate\Database\Schema\Blueprint;
#use Illuminate\Support\Facades\Schema;

#return new class extends Migration
#{
    #public function up(): void
    #{
     #     Schema::create('visits', function (Blueprint $table) {
    #        $table->uuid('id')->primary();
    #        $table->uuid('person_id');
    #        $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
    #        $table->enum('visit_type', ['ILLNESS', 'INJURY', 'FOLLOW_UP', 'SCREENING', 'OTHER'])->default('ILLNESS');
    #        $table->timestamp('arrival_at');
    #        $table->timestamp('departure_at')->nullable();
    #        $table->enum('triage_level', ['LOW', 'MEDIUM', 'HIGH', 'EMERGENCY'])->default('LOW');
    #        $table->text('chief_complaint');
    #        $table->text('history_notes')->nullable();
    #        $table->text('assessment_notes')->nullable();
    #        $table->enum('disposition', [
    #            'TREATED_AND_RETURNED',
    #            'RESTED_IN_CLINIC',
    #            'SENT_HOME',
    #            'REFERRED',
    #            'AMBULANCE',
    #            'ADMITTED_OBS'
       #     ])->nullable();
    #           $table->uuid('created_by_user_id');
            // Foreign key constraint added in later migration after app_users table exists
    #        $table->timestamps();
            
    #        $table->index(['person_id', 'arrival_at']);
    #        $table->index(['triage_level', 'arrival_at']);
    #        $table->index('disposition');
      #1  });
    #}

   # public function down(): void
   # {
   #     Schema::dropIfExists('visits');
   # }
#};

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('person_id');

            // Visit classification
            $table->string('visit_type'); 
            $table->string('triage_level')->nullable(); // LOW, MEDIUM, HIGH, EMERGENCY

            // Timing
            $table->timestamp('arrival_at');
            $table->timestamp('departure_at')->nullable();

            // Clinical notes
            $table->text('chief_complaint');
            $table->text('history_notes')->nullable();
            $table->text('assessment_notes')->nullable();

            // Outcome
            $table->string('disposition')->nullable();
            // e.g. TREATED_AND_RETURNED, RESTED_IN_CLINIC, REFERRED, SENT_HOME

            // Audit
            $table->uuid('created_by_user_id');

            $table->timestamps();
            $table->softDeletes();

            // Relationships
            $table->foreign('person_id')
                ->references('id')
                ->on('persons')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
