<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('visit_id');
            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('cascade');
            $table->uuid('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('restrict');
            $table->string('dose'); // e.g., 1 tab
            $table->string('frequency'); // e.g., BD
            $table->string('duration'); // e.g., 3 days
            $table->text('instructions')->nullable();
            $table->timestamps();
            
            $table->index(['visit_id', 'item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};
