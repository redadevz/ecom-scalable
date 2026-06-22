<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Standardize all money columns to decimal(10,2). order_items.unit_price is
     * already decimal(10,2); the rest were decimal(8,2).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->change();
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total', 10, 2)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->change();
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->decimal('unit_price', 8, 2)->change();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('total', 8, 2)->change();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 8, 2)->change();
        });
    }
};
