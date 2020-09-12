<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('id_invoice');
            // $table->unsignedBigInteger('id_cashier');
            $table->foreignId('id_cashier');
            $table->dateTime('date', 0);
            $table->bigInteger('total');
            $table->bigInteger('buyer_money');
            $table->bigInteger('change_money');
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
        Schema::dropIfExists('invoices');
    }
}
