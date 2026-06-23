<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bar_codes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('items');
            // Source schema stored an image; we store the barcode value (unique).
            $table->string('bar_code')->unique();
            $table->boolean('is_active')->default(true);
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bar_codes');
    }
};
