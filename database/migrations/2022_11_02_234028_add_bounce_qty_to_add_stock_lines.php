<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBounceQtyToAddStockLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('add_stock_lines', function (Blueprint $table) {
            $table->double('sell_price');
            $table->integer('bounce_qty')->nullable();
            $table->double('profit_bounce')->nullable();
            $table->double('bounce_purchase_price')->nullable();
            $table->string('bounce_convert_status_expire')->nullable();
            $table->string('bounce_expiry_warning')->nullable();
            $table->string('bounce_expiry_date')->nullable();
            $table->string('bounce_manufacturing_date')->nullable();
            $table->string('bounce_batch_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('add_stock_lines', function (Blueprint $table){
            $table->dropColumn('sell_price');
            $table->dropColumn('bounce_qty');
            $table->dropColumn('profit_bounce');
            $table->dropColumn('bounce_purchase_price');
            $table->dropColumn('bounce_convert_status_expire');
            $table->dropColumn('bounce_expiry_warning');
            $table->dropColumn('bounce_expiry_date');
            $table->dropColumn('bounce_manufacturing_date');
            $table->dropColumn('bounce_batch_number');
        });
    }
}
