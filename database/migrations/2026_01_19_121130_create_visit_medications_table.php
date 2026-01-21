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
        Schema::create('visit_medications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // References
            $table->uuid('visit_id');
            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('cascade');
            
            $table->uuid('medication_id');
            $table->foreign('medication_id')->references('id')->on('medications')->onDelete('restrict');
            
            $table->uuid('inventory_id')->nullable(); // Track which batch was used
            $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('set null');
            
            // Dosage Information
            $table->string('dosage')->nullable(); // e.g., "1 tablet", "10ml"
            $table->string('frequency')->nullable(); // e.g., "Twice daily", "As needed"
            $table->integer('quantity_issued')->default(1);
            $table->text('instructions')->nullable();
            
            // Timing
            $table->dateTime('issued_at')->nullable();
            $table->unsignedBigInteger('issued_by')->nullable(); // User ID
            $table->foreign('issued_by')->references('id')->on('users')->onDelete('set null');
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['visit_id', 'medication_id']);
            $table->index('issued_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visit_medications');
    }
};
