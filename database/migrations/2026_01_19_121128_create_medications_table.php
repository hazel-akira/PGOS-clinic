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
        Schema::create('medications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            // Medication Information
            $table->string('name'); // e.g., "Paracetamol 500mg"
            $table->string('generic_name')->nullable();
            $table->string('manufacturer')->nullable();
            $table->text('description')->nullable();
            
            // Dosage Information
            $table->string('dosage_form')->nullable(); // e.g., "Tablet", "Syrup", "Injection"
            $table->string('strength')->nullable(); // e.g., "500mg", "10ml"
            $table->text('dosage_instructions')->nullable();
            
            // Classification
            $table->string('category')->nullable(); // e.g., "Pain Relief", "Antibiotic", "First Aid"
            $table->boolean('requires_prescription')->default(false);
            $table->boolean('is_controlled_substance')->default(false);
            
            // Status
            $table->boolean('is_active')->default(true);
            
            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('name');
            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};
