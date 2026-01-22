<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_txns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('txn_type', ['RECEIVE', 'ISSUE', 'ADJUST', 'EXPIRE', 'RETURN']);
            $table->uuid('batch_id');
            $table->foreign('batch_id')->references('id')->on('stock_batches')->onDelete('cascade');
            $table->integer('qty'); // positive for in, negative for out
            $table->text('reason')->nullable();
            $table->uuid('visit_id')->nullable();
            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('set null');
            $table->uuid('performed_by_user_id');
            $table->foreign('performed_by_user_id')->references('id')->on('app_users')->onDelete('restrict');
            $table->timestamp('performed_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['batch_id', 'performed_at']);
            $table->index('txn_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_txns');
    }
};
