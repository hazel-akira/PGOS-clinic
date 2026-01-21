<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('visit_id');
            
            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('cascade');
            $table->text('description');
            $table->text('instructions')->nullable();
            $table->text('procedure_text'); // e.g., wound dressing

            $table->text('notes')->nullable();
            $table->uuid('performed_by_user_id');
            $table->foreign('performed_by_user_id')->references('id')->on('app_users')->onDelete('restrict');
            $table->timestamp('performed_at')->useCurrent();
            $table->timestamps();
            
            $table->index(['visit_id', 'performed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
