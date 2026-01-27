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

            $table->uuid('inventory_id');
            $table->foreign('inventory_id')
                ->references('id')
                ->on('inventory')
                ->onDelete('cascade');

            $table->string('batch_no');
            $table->date('expiry_date')->nullable();

            $table->integer('qty_on_hand')->default(0);
            $table->decimal('unit_cost', 10, 2)->nullable();

            $table->uuid('supplier_id')->nullable();
            $table->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['inventory_id', 'expiry_date']);
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('stock_batches');
    }
};
