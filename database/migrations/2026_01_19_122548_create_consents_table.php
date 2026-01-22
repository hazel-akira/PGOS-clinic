<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('person_id');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->enum('consent_type', [
                'TREATMENT_GENERAL',
                'EMERGENCY',
                'DATA_PROCESSING',
                'REFERRAL',
                'IMMUNIZATION',
                'OTHER'
            ]);
            $table->string('given_by'); // guardian name / adult patient
            $table->string('relationship')->nullable();
            $table->enum('channel', ['SIGNED_FORM', 'SMS', 'EMAIL', 'VERBAL', 'PORTAL']);
            $table->text('consent_text_version'); // to prove what they agreed to
            $table->timestamp('given_at');
            $table->timestamp('expires_at')->nullable();
            $table->uuid('evidence_attachment_id')->nullable();
            $table->timestamps();
            
            $table->index(['person_id', 'consent_type']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consents');
    }
};
