<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Till (cash-register) sessions — a cashier opens the drawer with a starting
 * float, rings up sales, then closes it by counting the cash. The X report is
 * a live read of an open session; the Z report is the closing summary with the
 * expected-vs-counted variance.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('till_sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('store_id')->nullable()->index();
            $table->unsignedBigInteger('opened_by')->nullable();
            $table->unsignedBigInteger('closed_by')->nullable();
            $table->decimal('opening_float', 15, 3)->default(0);
            $table->decimal('cash_sales', 15, 3)->nullable();
            $table->decimal('other_sales', 15, 3)->nullable();
            $table->decimal('expected_cash', 15, 3)->nullable();
            $table->decimal('counted_cash', 15, 3)->nullable();
            $table->decimal('variance', 15, 3)->nullable();
            $table->string('status', 20)->default('open')->index();
            $table->text('notes')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('till_sessions');
    }
};
