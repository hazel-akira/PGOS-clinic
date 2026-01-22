<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('visit_id');
            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('cascade');
            $table->text('diagnosis_text');
            $table->string('icd10_code')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['visit_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};
