<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->enum('provider', ['stripe', 'paypal', 'bank_transfer']);
            $table->string('provider_reference')->nullable();
            $table->enum('status', ['pending', 'paid', 'failed']);
            $table->decimal('amount', 8, 2);
            $table->enum('currency', ['usd', 'eur', 'gbp']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
