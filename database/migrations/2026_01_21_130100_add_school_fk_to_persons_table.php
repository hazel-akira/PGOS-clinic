<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->uuid('school_id')->nullable()->change();
            $table->uuid('campus_id')->nullable()->change();
        });

        Schema::table('persons', function (Blueprint $table) {
            $table->foreign('school_id')->references('id')->on('schools')->onDelete('set null');
            // campus_id kept nullable for future expansion; no FK until campus structure exists
        });
    }

    public function down(): void
    {
        Schema::table('persons', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
        });
    }
};
