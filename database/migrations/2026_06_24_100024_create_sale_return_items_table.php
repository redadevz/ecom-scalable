<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('sale_return_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_return_id')->constrained('sale_returns')->cascadeOnDelete();
            $table->foreignId('order_line_id')->constrained('order_lines');
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();
            $table->string('line_no', 50)->nullable();
            $table->integer('quantity');
            $table->integer('return_quantity');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sale_return_items'); }
};
