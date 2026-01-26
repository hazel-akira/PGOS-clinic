<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('allergies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('person_id');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->string('allergen'); // e.g., penicillin, peanuts
            $table->text('reaction')->nullable();
            $table->enum('severity', ['MILD', 'MODERATE', 'SEVERE'])->default('MILD');
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();

            $table->index(['person_id', 'severity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('allergies');
    }
};
