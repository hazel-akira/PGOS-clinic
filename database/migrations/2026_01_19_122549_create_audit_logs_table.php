<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('app_users')->onDelete('set null');
            $table->string('action'); // CREATE_VISIT, UPDATE_VISIT, VIEW_PROFILE, etc.
            $table->string('entity_type');
            $table->uuid('entity_id');
            $table->timestamp('timestamp')->useCurrent();
            $table->string('ip_address')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['entity_type', 'entity_id', 'timestamp']);
            $table->index(['user_id', 'timestamp']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
