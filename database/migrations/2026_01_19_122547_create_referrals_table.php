<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('visit_id');
            $table->foreign('visit_id')->references('id')->on('visits')->onDelete('cascade');
            $table->string('referred_to'); // facility name
            $table->text('reason');
            $table->enum('transport_mode', ['PARENT_PICKUP', 'SCHOOL_VEHICLE', 'AMBULANCE', 'OTHER'])->default('PARENT_PICKUP');
            $table->uuid('referral_letter_attachment_id')->nullable();
            $table->enum('status', ['PENDING', 'COMPLETED', 'CANCELLED'])->default('PENDING');
            $table->timestamps();
            
            $table->index(['visit_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
