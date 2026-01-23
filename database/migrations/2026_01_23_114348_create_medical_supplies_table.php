<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_supplies', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->string('unit_of_measure')->nullable(); // pcs, pkts, bottles, dozens
            $table->string('category')->nullable();        // Dressings, IV Supplies, etc
            $table->boolean('is_active')->default(true);

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
            $table->index('category');
            $table->index('is_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_supplies');
    }
};
