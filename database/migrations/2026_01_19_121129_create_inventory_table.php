<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Medication reference
            $table->uuid('medication_id');
            $table->foreign('medication_id')->references('id')->on('medications')->onDelete('cascade');

            // Stock management
            $table->integer('minimum_stock_level')->default(10);
            $table->integer('quantity_available')->default(0);
            $table->boolean('is_low_stock')->default(false);
            $table->timestamp('low_stock_alerted_at')->nullable();

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['medication_id']);
            $table->index('is_low_stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
