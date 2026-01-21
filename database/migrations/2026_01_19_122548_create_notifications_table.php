<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('person_id');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->uuid('visit_id')->nullable();
            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('set null');
            $table->enum('channel', ['SMS', 'EMAIL', 'WHATSAPP', 'IN_APP']);
            $table->text('message_template');
            $table->json('message_payload')->nullable(); // avoid storing raw health details
            $table->enum('status', ['QUEUED', 'SENT', 'FAILED'])->default('QUEUED');
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
            
            $table->index(['person_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
