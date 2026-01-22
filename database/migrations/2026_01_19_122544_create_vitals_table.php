<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vitals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('visit_id');
            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('cascade');
            $table->decimal('temp_c', 4, 1)->nullable();
            $table->integer('bp_systolic')->nullable();
            $table->integer('bp_diastolic')->nullable();
            $table->integer('pulse')->nullable();
            $table->integer('resp_rate')->nullable();
            $table->integer('spo2')->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->decimal('height_cm', 5, 2)->nullable();
            $table->timestamp('taken_at')->useCurrent();
            $table->uuid('taken_by_user_id');
            $table->foreign('taken_by_user_id')->references('id')->on('app_users')->onDelete('restrict');
            $table->timestamps();
            
            $table->index(['visit_id', 'taken_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vitals');
    }
};
