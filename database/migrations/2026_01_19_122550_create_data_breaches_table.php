<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_breaches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('detected_at')->useCurrent();
            $table->text('description');
            $table->text('impact_summary');
            $table->timestamp('reported_to_odpc_at')->nullable();
            $table->timestamp('data_subjects_notified_at')->nullable();
            $table->enum('status', ['OPEN', 'CONTAINED', 'CLOSED'])->default('OPEN');
            $table->timestamps();
            
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_breaches');
    }
};
