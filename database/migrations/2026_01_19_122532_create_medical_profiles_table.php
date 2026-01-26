<?php

// use Illuminate\Database\Migrations\Migration;
// #use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//    public function up(): void
//   {
// Schema::create('medical_profiles', function (Blueprint $table) {
//    $table->uuid('id')->primary();
//       $table->uuid('person_id')->unique();
//        $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
//        $table->string('blood_group')->nullable();
//        $table->text('allergies_summary')->nullable();
//        $table->text('chronic_conditions_summary')->nullable();
//        $table->text('special_needs_notes')->nullable();
//        $table->timestamp('last_reviewed_at')->nullable();
//        $table->timestamps();
//    });
// }
// }

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_profiles', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('person_id')->unique();

            // Core medical info
            $table->string('blood_group')->nullable();
            $table->text('allergies_summary')->nullable();
            $table->text('chronic_conditions_summary')->nullable();
            $table->text('special_needs_notes')->nullable();

            // Governance
            $table->timestamp('last_reviewed_at')->nullable();

            $table->timestamps();

            // Relationships
            $table->foreign('person_id')
                ->references('id')
                ->on('persons')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_profiles');
    }
};
