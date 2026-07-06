<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Schema drift fix: the payments/refunds create-migrations never added the
 * `comments` column, but the models/PaymentService write to it (and the dev
 * DB already has it). Without this, a fresh migrate produces tables missing
 * `comments` and payment/refund creation fails. Guarded so it is a no-op
 * where the column already exists.
 */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['payments', 'refunds'] as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'comments')) {
                Schema::table($table, function (Blueprint $t): void {
                    $t->string('comments')->nullable();
                });
            }
        }
    }

    public function down(): void
    {
        foreach (['payments', 'refunds'] as $table) {
            if (Schema::hasColumn($table, 'comments')) {
                Schema::table($table, function (Blueprint $t): void {
                    $t->dropColumn('comments');
                });
            }
        }
    }
};
