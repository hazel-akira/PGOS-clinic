<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chronic_conditions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('person_id');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->string('condition'); // e.g., asthma, diabetes
            $table->text('notes')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamp('recorded_at')->useCurrent();
            $table->timestamps();

            $table->index(['person_id', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chronic_conditions');
    }
};
