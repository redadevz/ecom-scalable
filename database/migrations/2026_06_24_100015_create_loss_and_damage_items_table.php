<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('loss_and_damage_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loss_and_damage_id')->constrained('loss_and_damages')->cascadeOnDelete();
            $table->foreignId('item_id')->constrained('items');
            $table->integer('quantity');
            $table->string('description')->nullable();
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('loss_and_damage_items'); }
};
