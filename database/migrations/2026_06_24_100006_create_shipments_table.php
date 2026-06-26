<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('order_id')->constrained('order_headers')->cascadeOnDelete();
            $table->foreignId('shipment_city_id')->nullable()->constrained('cities');
            $table->foreignId('picked_up_by')->nullable()->constrained('craftable_pro_users')->nullOnDelete();
            $table->string('shipment_address');
            $table->string('gps_location', 50)->nullable();
            $table->string('postal_code', 50)->nullable();
            $table->string('shipment_notes')->nullable();
            $table->timestamp('picked_up_time')->nullable();
            $table->timestamp('shipped_time')->nullable();
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('shipments'); }
};
