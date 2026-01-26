<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_subject_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('person_id');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->enum('request_type', [
                'ACCESS',
                'RECTIFY',
                'DELETE',
                'PORTABILITY',
                'RESTRICT',
            ]);
            $table->timestamp('requested_at')->useCurrent();
            $table->enum('status', ['OPEN', 'IN_PROGRESS', 'COMPLETED', 'REJECTED'])->default('OPEN');
            $table->timestamp('closed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['person_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_subject_requests');
    }
};
