<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDebtTransactionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debt_transaction_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('debt_payment_id');
            $table->foreign('debt_payment_id')->references('id')->on('debt_payments')->onDelete('cascade');
            $table->unsignedBigInteger('transaction_payment_id');
            $table->foreign('transaction_payment_id')->references('id')->on('transaction_payments')->onDelete('cascade');
            $table->decimal('amount', 15, 4);
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
        Schema::dropIfExists('debt_transaction_payments');
    }
}
