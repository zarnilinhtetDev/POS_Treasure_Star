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
        Schema::table('in_outs', function (Blueprint $table) {
            $table->string('retail_price',191)->nullable()->after('total_quantity');;
            $table->string('wholesale_price',191)->nullable()->after('retail_price');;
            $table->string('buy_price',191)->nullable()->after('wholesale_price');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('in_outs', function (Blueprint $table) {
            $table->dropColumn('retail_price');
            $table->dropColumn('wholesale_price');
            $table->dropColumn('buy_price');
        });
    }
};