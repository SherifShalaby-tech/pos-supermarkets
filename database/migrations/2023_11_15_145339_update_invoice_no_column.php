<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInvoiceNoColumn extends Migration
{
    /* +++++++++++++++++ up() +++++++++++++++++ */
    public function up()
    {
        // Remove the nullable constraint
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('invoice_no')->change();
        });

        // Add the index to the column
        Schema::table('transactions', function (Blueprint $table) {
            $table->index('invoice_no');
        });
    }
    /* +++++++++++++++++ down() +++++++++++++++++ */
    public function down()
    {
        // Remove the index
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['invoice_no']);
        });

        // Add the nullable constraint back
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('invoice_no')->nullable()->change();
        });
    }
}
