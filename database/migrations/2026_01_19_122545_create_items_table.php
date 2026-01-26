<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('form'); // tablet, syrup, cream
            $table->string('strength')->nullable();
            $table->string('unit'); // tabs, ml
            $table->integer('reorder_level')->default(0);
            $table->boolean('is_medicine')->default(true);
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->index(['is_medicine', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
