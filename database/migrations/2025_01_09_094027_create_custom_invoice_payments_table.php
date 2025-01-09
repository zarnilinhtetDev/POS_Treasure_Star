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
        Schema::create('custom_invoice_payments', function (Blueprint $table) {
            $table->id();
            $table->string('custom_invoice_id', 191)->nullable();
            $table->string('payment_method', 191)->nullable();
            $table->string('payment_amount', 191)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_invoice_payments');
    }
};
