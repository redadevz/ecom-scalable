<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('order_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('discount_id')->constrained('discounts');
            $table->foreignId('order_id')->constrained('order_headers')->cascadeOnDelete();
            $table->decimal('value', 15, 3)->default(0);
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('order_discounts'); }
};
