<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Prevent deleting order history when a product variant is removed.
     * order_items keep the row, variant_id is set to NULL (name is kept in variant_name).
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('variant_id')->references('id')->on('product_variants')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->foreign('variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }
};
