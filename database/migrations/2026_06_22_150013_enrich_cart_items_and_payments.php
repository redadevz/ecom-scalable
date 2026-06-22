<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * cart_items: one line per product (merge quantities instead of duplicating).
     * payments: support refunds, record when paid, store the gateway payload,
     * and prevent a duplicated webhook from double-recording a payment.
     */
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->decimal('unit_price', 10, 2)->change();
            $table->unique(['cart_id', 'product_id']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
            $table->json('meta')->nullable()->after('provider_reference');
            $table->timestamp('paid_at')->nullable()->after('status');
            $table->enum('status', ['pending', 'paid', 'failed', 'refunded'])
                ->default('pending')->change();
            $table->unique(['provider', 'provider_reference']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropUnique(['provider', 'provider_reference']);
            $table->dropColumn(['meta', 'paid_at']);
            $table->decimal('amount', 8, 2)->change();
            $table->enum('status', ['pending', 'paid', 'failed'])->change();
        });

        // The composite unique backs the cart_id FK index, so drop/re-add the FK around it.
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['cart_id']);
        });
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['cart_id', 'product_id']);
            $table->foreign('cart_id')->references('id')->on('carts')->cascadeOnDelete();
            $table->decimal('unit_price', 8, 2)->change();
        });
    }
};
