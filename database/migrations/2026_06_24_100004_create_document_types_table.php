<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('document_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_category_id')->constrained('document_categories');
            $table->string('name', 50);
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('document_types'); }
};
