<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashierTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashier', function (Blueprint $table) {
            $table->id('id_cashier');
            $table->string('photo', 20);
            $table->string('username', 10);
            $table->string('name', 20);
            $table->string('password', 100);
            // $table->unsignedBigInteger('id_product_storage');
            $table->foreignId('id_product_storage');
            // $table->unsignedBigInteger('id_store');
            $table->foreignId('id_store');
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
        Schema::dropIfExists('cashier');
    }
}
