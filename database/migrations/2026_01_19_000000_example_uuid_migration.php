<?php

/**
 * Example Migration Template for UUID-based Models
 * 
 * This is a template showing how to create migrations for domain models
 * that will use UUIDs instead of auto-incrementing IDs.
 * 
 * For healthcare systems, UUIDs provide:
 * - Better privacy (prevents enumeration attacks)
 * - Easier multi-tenant support
 * - Better for distributed systems
 * 
 * DELETE THIS FILE before implementing actual domain models.
 */

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
        Schema::create('example_patients', function (Blueprint $table) {
            // Use uuid() instead of id() for primary key
            $table->uuid('id')->primary();
            
            // Standard fields
            $table->string('name');
            $table->string('student_id')->unique()->nullable();
            $table->date('date_of_birth')->nullable();
            
            // Foreign keys should also be UUIDs if referencing UUID tables
            // $table->uuid('created_by_user_id')->nullable();
            // $table->foreign('created_by_user_id')->references('id')->on('users');
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes(); // Consider soft deletes for audit compliance
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('example_patients');
    }
};
