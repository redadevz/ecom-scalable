<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('document_type_id')->constrained('document_types');
            $table->foreignId('sale_order_id')->nullable()->constrained('order_headers')->nullOnDelete();
            $table->foreignId('purchase_id')->nullable()->constrained('purchases')->nullOnDelete();
            $table->foreignId('stock_return_id')->nullable()->constrained('stock_returns')->nullOnDelete();
            $table->foreignId('inventory_count_id')->nullable()->constrained('inventory_counts')->nullOnDelete();
            $table->foreignId('loss_and_damage_id')->nullable()->constrained('loss_and_damages')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('craftable_pro_users')->nullOnDelete();
            $table->string('number', 25);
            $table->string('external_number', 25)->nullable();
            $table->string('description')->nullable();
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('documents'); }
};
