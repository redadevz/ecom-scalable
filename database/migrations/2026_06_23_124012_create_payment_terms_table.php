<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_terms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_channel_id')->constrained('sale_channels');
            $table->foreignId('delivery_type_id')->constrained('delivery_types');
            $table->foreignId('payment_method_id')->constrained('payment_methods');
            $table->foreignId('payment_time_id')->constrained('payment_times');
            $table->boolean('is_allowed')->default(true);
            $table->boolean('is_active')->default(true);
            $table->string('comments', 1000)->nullable();
            $table->timestamps();

            $table->unique(
                ['sale_channel_id', 'delivery_type_id', 'payment_method_id', 'payment_time_id'],
                'uk_payment_term'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_terms');
    }
};
