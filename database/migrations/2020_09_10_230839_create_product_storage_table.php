<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductStorageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_storage', function (Blueprint $table) {
            $table->id('id_product_storage');
            // $table->unsignedBigInteger('id_product');
            $table->foreignId('id_product');
            // $table->unsignedBigInteger('id_store');
            $table->foreignId('id_store');
            $table->integer('stock');
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
        Schema::dropIfExists('product_storage');
    }
}
