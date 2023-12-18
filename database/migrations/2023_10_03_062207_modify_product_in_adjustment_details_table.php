<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyProductInAdjustmentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_in_adjustment_details', function (Blueprint $table) {
            $table->integer('old_stock')->nullable()->change();
            $table->integer('new_stock')->nullable()->change();
            $table->integer('shortage')->nullable()->change();
            $table->decimal('shortage_value', 15, 4)->nullable()->change();
            $table->decimal('old_purchase_price', 15, 4)->nullable();
            $table->decimal('new_purchase_price', 15, 4)->nullable();
            $table->decimal('old_sell_price', 15, 4)->nullable();
            $table->decimal('new_sell_price', 15, 4)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_in_adjustment_details', function (Blueprint $table) {
            //
        });
    }
}
