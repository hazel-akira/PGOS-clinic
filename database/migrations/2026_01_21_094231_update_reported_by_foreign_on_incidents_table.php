<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            // Drop old foreign key if it exists
            $table->dropForeign(['reported_by_user_id']);
        });

        Schema::table('incidents', function (Blueprint $table) {
            // Add new foreign key to users table
            $table->foreign('reported_by_user_id')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropForeign(['reported_by_user_id']);
        });

        Schema::table('incidents', function (Blueprint $table) {
            $table->foreign('reported_by_user_id')
                ->references('id')
                ->on('app_users')
                ->onDelete('restrict');
        });
    }
};
