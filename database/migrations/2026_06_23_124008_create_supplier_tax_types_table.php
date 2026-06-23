<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supplier_tax_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->string('name', 50);
            $table->string('code', 25);
            $table->string('description')->nullable();
            $table->boolean('is_percentage')->default(true);
            $table->decimal('value', 15, 3);
            $table->timestamp('start_time');
            $table->timestamp('end_time')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('comments', 1000)->nullable();
            $table->timestamps();

            $table->unique(['supplier_id', 'name'], 'uk_sup_tax_name');
            $table->unique(['supplier_id', 'code'], 'uk_sup_tax_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supplier_tax_types');
    }
};
