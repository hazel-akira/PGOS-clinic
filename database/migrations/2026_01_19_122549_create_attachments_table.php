<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('owner_type', [
                'VISIT',
                'REFERRAL',
                'CONSENT',
                'IMMUNIZATION',
                'INCIDENT',
                'PROFILE',
            ]);
            $table->uuid('owner_id');
            $table->string('file_name');
            $table->string('file_mime');
            $table->string('storage_path');
            $table->string('hash_sha256');
            $table->uuid('uploaded_by_user_id');
            $table->foreign('uploaded_by_user_id')->references('id')->on('app_users')->onDelete('restrict');
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamps();

            $table->index(['owner_type', 'owner_id']);
            $table->index('hash_sha256');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
