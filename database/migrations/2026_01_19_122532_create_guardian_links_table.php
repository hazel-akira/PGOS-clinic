<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardian_links', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('student_person_id');
            $table->foreign('student_person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->uuid('guardian_id');
            $table->foreign('guardian_id')->references('id')->on('guardians')->onDelete('cascade');
            $table->boolean('is_primary')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['student_person_id', 'guardian_id']);
            $table->index('is_primary');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guardian_links');
    }
};
