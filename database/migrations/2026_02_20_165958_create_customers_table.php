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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->integer('billing_day');

            $table->decimal('base_monthly_payment', 10, 2)->default(0);
            $table->boolean('has_iva')->default(false);
            $table->boolean('has_renta')->default(false);
            $table->decimal('iva_amount', 10, 2)->default(0);
            $table->decimal('renta_amount', 10, 2)->default(0);
            $table->decimal('final_monthly_payment', 10, 2)->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
