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
        Schema::create('custom_make_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_invoice_id')->nullable();
            $table->string('custom_invoice_no')->nullable();
            $table->string('invoice_record')->nullable();
            $table->string('payment_date', 191)->nullable();
            $table->string('payment_method', 191)->nullable();
            $table->string('amount')->nullable();
            $table->string('note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_make_payments');
    }
};
