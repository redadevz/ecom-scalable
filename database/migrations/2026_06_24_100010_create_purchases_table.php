<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->timestamp('entry_stock_time')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('purchases'); }
};
