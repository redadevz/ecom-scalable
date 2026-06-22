<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * A real order carries a human reference, a currency, an auditable money
     * breakdown (subtotal/tax/shipping/discount/total), a snapshot of the
     * shipping address, lifecycle timestamps, and a fuller status set.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->nullable()->after('id');
            $table->decimal('subtotal', 10, 2)->default(0)->after('email');
            $table->decimal('tax', 10, 2)->default(0)->after('subtotal');
            $table->decimal('shipping', 10, 2)->default(0)->after('tax');
            $table->decimal('discount', 10, 2)->default(0)->after('shipping');
            $table->decimal('total', 10, 2)->change();
            $table->char('currency', 3)->default('usd')->after('total');
            $table->json('shipping_address')->nullable()->after('currency');
            $table->timestamp('placed_at')->nullable()->after('shipping_address');
            $table->timestamp('paid_at')->nullable()->after('placed_at');
            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'completed', 'cancelled', 'refunded'])
                ->default('pending')->change();
        });

        // Backfill existing rows so we can enforce uniqueness.
        DB::statement("UPDATE orders SET order_number = CONCAT('ORD-', LPAD(id, 6, '0')) WHERE order_number IS NULL");
        DB::statement("UPDATE orders SET subtotal = total WHERE subtotal = 0");

        Schema::table('orders', function (Blueprint $table) {
            $table->unique('order_number');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
            $table->dropIndex(['status']);
            $table->dropUnique(['order_number']);
            $table->dropColumn([
                'order_number', 'subtotal', 'tax', 'shipping', 'discount',
                'currency', 'shipping_address', 'placed_at', 'paid_at',
            ]);
            $table->decimal('total', 8, 2)->change();
            $table->enum('status', ['pending', 'completed', 'cancelled'])->change();
        });
    }
};
