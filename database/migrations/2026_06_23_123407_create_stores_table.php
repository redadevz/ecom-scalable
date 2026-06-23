<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('city_id')->constrained('cities');
            $table->foreignId('language_id')->constrained('languages');
            $table->foreignId('currency_id')->constrained('currencies');
            $table->string('code')->nullable()->unique();
            $table->string('name')->unique();
            $table->boolean('is_active')->default(true);
            $table->string('legal_entity_name');
            $table->string('tax_code');
            $table->string('address');
            $table->string('registration_number');
            $table->string('phone')->nullable();
            $table->string('fax')->nullable();
            $table->string('email', 50)->nullable();
            $table->string('logo')->nullable();
            $table->string('bank_branch', 255)->nullable();
            $table->string('bank_code', 50)->nullable();
            $table->string('bank_account', 50)->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
