<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('order_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('order_headers')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
            $table->foreignId('replied_by')->nullable()->constrained('craftable_pro_users')->nullOnDelete();
            $table->unsignedTinyInteger('rating')->nullable();
            $table->string('review')->nullable();
            $table->timestamp('review_time')->nullable();
            $table->string('reply')->nullable();
            $table->timestamp('reply_time')->nullable();
            $table->boolean('is_compensated')->default(false);
            $table->string('compensation_value')->nullable();
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('order_reviews'); }
};
