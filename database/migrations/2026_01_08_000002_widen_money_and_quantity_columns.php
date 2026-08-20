<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Widen money/quantity columns so large orders (stress test with huge qty)
     * do not overflow decimal(10,2) / integer columns on MySQL.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 15, 2)->change();
            $table->decimal('discount_price', 15, 2)->nullable()->change();
            $table->bigInteger('stock')->default(0)->change();
            $table->bigInteger('weight')->default(0)->change();
            $table->bigInteger('sold_count')->default(0)->change();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->bigInteger('quantity')->default(0)->change();
            $table->decimal('price', 15, 2)->change();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->bigInteger('quantity')->default(1)->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_amount', 15, 2)->change();
            $table->decimal('shipping_cost', 15, 2)->default(0)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->bigInteger('quantity')->change();
            $table->decimal('price', 15, 2)->change();
            $table->decimal('total', 15, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
            $table->decimal('discount_price', 10, 2)->nullable()->change();
            $table->integer('stock')->default(0)->change();
            $table->integer('weight')->default(0)->change();
            $table->integer('sold_count')->default(0)->change();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->integer('quantity')->default(0)->change();
            $table->decimal('price', 10, 2)->change();
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total_amount', 10, 2)->change();
            $table->decimal('shipping_cost', 10, 2)->default(0)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->integer('quantity')->change();
            $table->decimal('price', 10, 2)->change();
            $table->decimal('total', 10, 2)->change();
        });
    }
};
