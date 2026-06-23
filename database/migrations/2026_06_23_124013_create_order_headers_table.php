<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('sale_channel_id')->constrained('sale_channels');
            $table->foreignId('delivery_type_id')->constrained('delivery_types');
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->foreignId('payment_time_id')->constrained('payment_times');
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('loyalty_card_id')->nullable()->constrained('loyalty_cards')->nullOnDelete();
            // Staff audit fields replace the source schema's *_Emp_Login_ID — Craftable users.
            $table->foreignId('created_by')->nullable()->constrained('craftable_pro_users')->nullOnDelete();
            $table->foreignId('approved_by')->nullable()->constrained('craftable_pro_users')->nullOnDelete();
            $table->foreignId('managed_by')->nullable()->constrained('craftable_pro_users')->nullOnDelete();

            $table->string('order_no', 50)->unique();
            $table->string('customer_notes')->nullable();

            $table->decimal('price_before_tax', 15, 3);
            $table->decimal('total_tax_value', 15, 3);
            $table->decimal('price_after_tax', 15, 3); // source said int — treated as money
            $table->decimal('price_before_discount', 15, 3);
            $table->decimal('order_items_discount', 15, 3);
            $table->decimal('order_discount', 15, 3);
            $table->decimal('total_discount_value', 15, 3);
            $table->decimal('price_after_discount', 15, 3);
            $table->decimal('price_adjustment', 15, 3)->nullable();
            $table->string('price_adjustment_reason')->nullable();
            $table->decimal('price', 15, 3);

            $table->string('latest_status', 50);
            $table->timestamp('latest_status_update');
            $table->boolean('is_submitted')->default(false);
            $table->timestamp('submitted_time')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamp('approved_time')->nullable();
            $table->boolean('is_canceled')->default(false);
            $table->timestamp('canceled_time')->nullable();
            $table->string('cancel_reason')->nullable();
            $table->boolean('is_scheduled')->default(false);
            $table->timestamp('scheduled_time')->nullable();
            $table->boolean('is_ready')->default(false);
            $table->timestamp('ready_time')->nullable();
            $table->boolean('is_delivered')->default(false);
            $table->timestamp('delivered_time')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->timestamp('payment_time')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_time')->nullable();
            $table->boolean('return_required')->default(false);
            $table->timestamp('return_time')->nullable();
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_headers');
    }
};
