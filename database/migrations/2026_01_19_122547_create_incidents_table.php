<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('person_id');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->enum('incident_type', ['INJURY', 'ACCIDENT', 'VIOLENCE', 'ALLERGIC_REACTION', 'OTHER']);
            $table->timestamp('occurred_at');
            $table->string('location'); // playground, dorm, lab
            $table->text('description');
            $table->enum('severity', ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'])->default('MEDIUM');
            $table->uuid('linked_visit_id')->nullable();
            $table->foreign('linked_visit_id')->references('id')->on('visits')->onDelete('set null');
            $table->uuid('reported_by_user_id');
            $table->foreign('reported_by_user_id')->references('id')->on('app_users')->onDelete('restrict');
            $table->text('actions_taken')->nullable();
            $table->boolean('parents_notified')->default(false);
            $table->timestamp('parents_notified_at')->nullable();
            $table->timestamps();

            $table->index(['person_id', 'occurred_at']);
            $table->index('severity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
