<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('app_roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name')->unique(); // CLINIC_NURSE, DOCTOR, ADMIN, PRINCIPAL_READONLY, etc.
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_roles');
    }
};
