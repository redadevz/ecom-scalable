<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_return_id')->constrained('sale_returns')->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->string('refund_no', 50)->nullable();
            $table->decimal('amount', 15, 3);
            $table->decimal('cash_paid', 15, 3)->nullable();
            $table->decimal('cash_change', 15, 3)->nullable();
            $table->timestamp('refund_time')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('refunds'); }
};
