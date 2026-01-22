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
            $table->uuid('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->string('batch_no')->nullable();
            $table->date('expiry_date')->nullable();
            $table->integer('qty_on_hand');
            $table->decimal('unit_cost', 12, 2)->nullable();
            $table->uuid('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');
            $table->timestamps();
            
            $table->index(['item_id', 'expiry_date']);
            $table->index('batch_no');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_batches');
    }
};
