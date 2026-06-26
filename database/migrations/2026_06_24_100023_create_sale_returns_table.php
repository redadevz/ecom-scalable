<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('sale_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('order_id')->constrained('order_headers')->cascadeOnDelete();
            $table->timestamp('entry_stock_time')->nullable();
            $table->boolean('is_refund_required')->default(false);
            $table->decimal('refund_amount', 15, 3)->default(0);
            $table->boolean('is_refunded')->default(false);
            $table->timestamp('refund_time')->nullable();
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sale_returns'); }
};
