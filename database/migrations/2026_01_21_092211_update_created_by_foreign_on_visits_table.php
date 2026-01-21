<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            // Drop the old foreign key to app_users if it exists
            try {
                $table->dropForeign(['created_by_user_id']);
            } catch (\Throwable $e) {
                // Ignore if constraint name is different or doesn't exist (SQLite tolerance)
            }

            // Point created_by_user_id to the standard users table instead
            $table->foreign('created_by_user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('visits', function (Blueprint $table) {
            // Revert back to app_users if needed
            try {
                $table->dropForeign(['created_by_user_id']);
            } catch (\Throwable $e) {
                // Ignore if not present
            }

            $table->foreign('created_by_user_id')
                ->references('id')
                ->on('app_users')
                ->restrictOnDelete();
        });
    }
};
