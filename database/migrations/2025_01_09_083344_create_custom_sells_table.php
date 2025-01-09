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
        Schema::create('custom_sells', function (Blueprint $table) {
            $table->string('custom_invoiceid')->nullable();

            // $table->unsignedBigInteger('item_id'); //
            // $table->foreign('item_id')->references('id')->on('items'); //
            $table->integer('customer_id')->nullable();
            $table->string('description', 191)->nullable();
            $table->string('product_qty', 191)->nullable();
            $table->string('product_price', 191)->nullable();
            $table->string('retail_price', 191)->nullable();
            $table->string('buy_price')->nullable();
            $table->string('discount', 191)->nullable();
            $table->string('unit', 191)->nullable();
            $table->string('exp_date', 191)->nullable();
            $table->string('part_number', 191)->nullable();
            $table->string('warehouse', 191)->nullable();
            $table->string('status')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_sells');
    }
};
