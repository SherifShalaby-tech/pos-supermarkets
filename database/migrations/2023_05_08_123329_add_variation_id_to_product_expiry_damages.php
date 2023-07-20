<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVariationIdToProductExpiryDamages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_expiry_damages', function (Blueprint $table) {
            $table->integer('variation_id', )->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_expiry_damages', function (Blueprint $table) {
            $table->dropColumn('variation_id');
        });
    }
}
