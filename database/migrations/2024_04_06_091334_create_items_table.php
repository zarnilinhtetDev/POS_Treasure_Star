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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable();
            $table->string('item_name', 191)->nullable();
            $table->string('barcode', 191)->nullable();
            $table->string('descriptions', 191)->nullable();
            $table->string('expired_date', 191)->nullable();
            $table->string('category', 191)->nullable();
            $table->string('price', 191)->nullable();
            $table->string('company_price', 191)->nullable();
            $table->string('quantity', 191)->nullable();
            $table->string('item_unit', 191)->nullable();
            $table->string('reorder_level_stock', 191)->nullable();
            $table->string('mingalar_market', 191)->nullable();
            $table->string('retail_price', 191)->nullable();
            $table->string('wholesale_price', 191)->nullable();
            $table->string('buy_price', 191)->nullable();
            $table->string('item_image', 191)->nullable();
            $table->string('parent_id', 191)->default('0');
         

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
