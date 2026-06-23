<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('order_id')->constrained('order_headers')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items');
            $table->string('line_no', 50);
            $table->string('description')->nullable();
            $table->string('customer_notes')->nullable();
            $table->integer('quantity');
            $table->decimal('current_item_cost', 15, 3);
            $table->integer('markup_percentage');
            $table->decimal('price_before_tax', 15, 3);
            $table->decimal('tax_value', 15, 3);
            $table->decimal('price_after_tax', 15, 3);
            $table->decimal('price_before_discount', 15, 3);
            $table->decimal('discount_value', 15, 3);
            $table->decimal('price_after_discount', 15, 3);
            $table->decimal('price_adjustment', 15, 3)->nullable();
            $table->string('price_adjustment_reason')->nullable();
            $table->decimal('price', 15, 3);
            $table->boolean('is_canceled')->default(false);
            $table->timestamp('canceled_time')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->boolean('return_required')->default(false);
            $table->integer('return_quantity')->nullable();
            $table->timestamp('return_time')->nullable();
            $table->string('customer_review')->nullable();
            $table->boolean('customer_like')->nullable();
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_lines');
    }
};
