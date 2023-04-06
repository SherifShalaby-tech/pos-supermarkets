<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManufacturingProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manufacturing_products', function (Blueprint $table) {
            $table->id();
            $table->enum("status",["0","1"])->comment("0 => mean material under manufacture , 1 => mean material replaced");
            $table->integer("product_id");
            $table->integer("manufacturing_id");
            $table->string("quantity");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('manufacturing_products');
    }
}
