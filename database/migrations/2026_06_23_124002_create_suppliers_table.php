<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained('stores');
            $table->foreignId('city_id')->constrained('cities');
            // Replaces the source schema's Created_Emp_Login_ID — staff are Craftable users.
            $table->foreignId('created_by')->nullable()->constrained('craftable_pro_users')->nullOnDelete();
            $table->string('code', 10)->unique();
            $table->string('phone', 50);
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->boolean('is_company')->default(false);
            $table->string('company_name')->nullable();
            $table->string('tax_number', 50)->nullable();
            $table->boolean('is_tax_exempted')->default(false);
            $table->string('billing_address');
            $table->string('postal_code', 50)->nullable();
            $table->string('email', 50);
            $table->boolean('is_active')->default(true);
            $table->string('comments', 1000)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
