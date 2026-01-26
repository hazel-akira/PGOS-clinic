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
        Schema::create('inventory', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Medication Reference
            $table->uuid('medication_id');
            $table->foreign('medication_id')->references('id')->on('medications')->onDelete('cascade');

            // Batch Information
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->date('manufacture_date')->nullable();

            // Stock Management
            $table->integer('quantity_in')->default(0);
            $table->integer('quantity_out')->default(0);
            $table->integer('quantity_available')->default(0); // Calculated: in - out
            $table->integer('minimum_stock_level')->default(10); // Alert threshold

            // Supplier Information
            $table->string('supplier_name')->nullable();
            $table->string('supplier_contact')->nullable();
            $table->date('purchase_date')->nullable();
            $table->decimal('unit_price', 10, 2)->nullable();

            // Location
            $table->string('storage_location')->nullable(); // e.g., "Cabinet A", "Fridge"
            $table->text('storage_notes')->nullable();

            // Status
            $table->boolean('is_expired')->default(false);
            $table->boolean('is_low_stock')->default(false);
            $table->date('low_stock_alerted_at')->nullable();

            // Audit
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['medication_id', 'expiry_date']);
            $table->index('is_low_stock');
            $table->index('is_expired');
            $table->index('batch_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory');
    }
};
