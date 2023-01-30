<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPnaltiesToDepositSafes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deposit_safes', function (Blueprint $table) {
            $table->text('penalties')->nullable();
            $table->text('cause_the_penalties')->nullable();
            $table->double('penalty_amount')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deposit_safes', function (Blueprint $table) {
            $table->dropColumn('penalties');
            $table->dropColumn('cause_the_penalties');
            $table->dropColumn('penalty_amount');
        });
    }
}
