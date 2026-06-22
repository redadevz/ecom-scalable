<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Storefront/catalogue integrity: unique slug, optional unique SKU, an
     * availability flag, non-negative stock, consistent money precision, and an
     * index on the filtered category column.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('sku')->nullable()->unique()->after('slug');
            $table->boolean('is_active')->default(true)->after('category');
            $table->unsignedInteger('stock')->default(0)->change();
            $table->decimal('price', 10, 2)->change();
            $table->unique('slug');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropUnique(['slug']);
            $table->dropUnique(['sku']);
            $table->dropColumn(['sku', 'is_active']);
            $table->decimal('price', 8, 2)->change();
            $table->integer('stock')->default(0)->change();
        });
    }
};
