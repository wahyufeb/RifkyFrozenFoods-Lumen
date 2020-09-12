<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_category', function (Blueprint $table) {
            $table->id('id_price_category');
            // $table->unsignedBigInteger('id_product');
            $table->foreignId('id_product');
            $table->enum('name', ['grosir', 'eceran']);
            $table->bigInteger('price');
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
        Schema::dropIfExists('price_category');
    }
}
