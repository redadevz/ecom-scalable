<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('order_line_id')->constrained('order_lines');
            $table->string('line_no', 50);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('invoice_lines'); }
};
