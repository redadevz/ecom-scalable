<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('stock_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_return_id')->constrained('stock_returns')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items');
            $table->integer('quantity');
            $table->decimal('supplier_price_before_tax', 15, 3);
            $table->decimal('supplier_tax_value', 15, 3);
            $table->decimal('supplier_price_after_tax', 15, 3);
            $table->decimal('supplier_discount_value', 15, 3)->default(0);
            $table->decimal('return_amount', 15, 3)->default(0);
            $table->string('description')->nullable();
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('stock_return_items'); }
};
