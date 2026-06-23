<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('item_category_id')->constrained('item_categories');
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('measure_unit_id')->constrained('measure_units');
            $table->string('sku_code', 25)->unique();
            $table->string('name', 50);
            $table->string('description')->nullable();
            $table->boolean('is_service')->default(false);
            $table->boolean('in_stock')->default(true);
            $table->boolean('using_default_quantity')->default(false);
            $table->integer('default_quantity')->nullable();
            $table->integer('current_stock_quantity')->default(0);
            $table->integer('preferred_stock_quantity')->default(0);
            $table->integer('min_stock_quantity')->default(0);
            $table->boolean('low_stock_warning')->default(false);
            $table->integer('low_stock_quantity')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
