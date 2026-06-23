<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items');
            $table->foreignId('store_id')->constrained('stores');
            // Replaces the source schema's Created_Emp_Login_ID — staff are Craftable users.
            $table->foreignId('created_by')->nullable()->constrained('craftable_pro_users')->nullOnDelete();
            $table->string('description')->nullable();
            $table->decimal('current_item_cost', 15, 3);
            $table->integer('markup_percentage');
            $table->decimal('price_before_tax', 15, 3);
            $table->decimal('tax_value', 15, 3);
            $table->decimal('price_after_tax', 15, 3);
            $table->decimal('sale_price', 15, 3);
            $table->boolean('price_change_allowed')->default(false);
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
