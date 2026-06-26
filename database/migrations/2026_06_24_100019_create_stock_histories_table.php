<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('item_id')->constrained('items');
            $table->foreignId('document_id')->nullable()->constrained('documents')->nullOnDelete();
            $table->integer('initial_stock_quantity')->default(0);
            $table->decimal('initial_item_cost', 15, 3)->default(0);
            $table->boolean('is_stock_entry')->default(true);
            $table->integer('quantity');
            $table->integer('current_stock_quantity')->default(0);
            $table->decimal('current_item_cost', 15, 3)->default(0);
            $table->string('description')->nullable();
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('stock_histories'); }
};
