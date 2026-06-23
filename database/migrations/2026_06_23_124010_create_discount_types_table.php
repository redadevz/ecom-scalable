<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discount_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('loyalty_card_type_id')->nullable()->constrained('loyalty_card_types')->nullOnDelete();
            $table->string('name', 50);
            $table->string('description')->nullable();
            $table->boolean('is_percentage')->default(true);
            $table->decimal('value', 15, 3);
            $table->string('coupon_code', 50)->nullable();
            $table->decimal('min_order_value', 15, 3)->default(0);
            $table->integer('min_item_quantity')->default(0);
            $table->boolean('apply_to_all')->default(false);
            $table->boolean('apply_to_next')->default(false);
            $table->decimal('max_discount_value', 15, 3)->default(0);
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discount_types');
    }
};
