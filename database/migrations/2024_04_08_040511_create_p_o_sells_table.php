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
        Schema::create('p_o_sells', function (Blueprint $table) {
            $table->id();
            $table->string('invoiceid',191);

            $table->integer('supplier_id')->nullable();
            $table->string('description',191)->nullable();
            $table->string('product_qty',191)->nullable();
            $table->string('product_price',191)->nullable();
            $table->string('discount',191)->nullable();
            $table->string('unit',191)->nullable();
            $table->string('exp_date',191)->nullable();
            $table->string('part_number',191)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('p_o_sells');
    }
};