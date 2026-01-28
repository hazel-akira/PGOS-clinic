<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add nullable inventory_id and deleted_at if they don't exist yet.
        if (! Schema::hasTable('stock_batches')) {
            return;
        }

        if (! Schema::hasColumn('stock_batches', 'inventory_id')) {
            Schema::table('stock_batches', function (Blueprint $table) {
                $table->uuid('inventory_id')->nullable()->after('id');
            });
        }

        if (! Schema::hasColumn('stock_batches', 'deleted_at')) {
            Schema::table('stock_batches', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('stock_batches')) {
            return;
        }

        Schema::table('stock_batches', function (Blueprint $table) {
            if (Schema::hasColumn('stock_batches', 'inventory_id')) {
                $table->dropColumn('inventory_id');
            }
            if (Schema::hasColumn('stock_batches', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
        });
    }
};
