<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('immunizations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('person_id');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->string('vaccine_name');
            $table->integer('dose_no')->nullable();
            $table->date('date_given')->nullable();
            $table->uuid('evidence_attachment_id')->nullable();
            $table->timestamps();

            $table->index(['person_id', 'date_given']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('immunizations');
    }
};
