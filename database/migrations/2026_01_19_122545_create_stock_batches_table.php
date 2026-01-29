<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_batches', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Link to inventory
            $table->uuid('inventory_id');
            $table->foreign('inventory_id')->references('id')->on('inventory')->onDelete('cascade');

            $table->string('batch_no');
            $table->integer('qty_on_hand')->default(0);
            $table->date('expiry_date')->nullable();
            $table->decimal('unit_cost', 10, 2)->nullable();

            // Optional supplier
            $table->uuid('supplier_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['inventory_id', 'batch_no']);
            $table->index('expiry_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_batches');
    }
};
