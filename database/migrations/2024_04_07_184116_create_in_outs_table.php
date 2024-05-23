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
        Schema::create('in_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_id')->nullable();
            $table->integer('items_id')->nullable();
            $table->string('quantity',191)->nullable();
            $table->string('total_quantity',191)->nullable();
            $table->string('company_price',191)->nullable();
            $table->string('mingalar_market',191)->nullable();
            $table->string('date',191)->nullable();
            $table->string('remark',191)->nullable();
            $table->string('in_out',191)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('in_outs');
    }
};