<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type', ['in', 'out', 'adjust', 'expire', 'return']);
            $table->uuid('stock_batch_id');
            $table->foreign('stock_batch_id')->references('id')->on('stock_batches')->onDelete('cascade');
            $table->integer('quantity');
            $table->text('notes')->nullable();
            $table->uuid('visit_id')->nullable();
            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('set null');
            $table->uuid('performed_by')->nullable();
            $table->foreign('performed_by')->references('id')->on('users')->onDelete('set null');
            $table->timestamp('performed_at')->useCurrent();
            $table->timestamps();

            $table->index(['stock_batch_id', 'performed_at']);
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
