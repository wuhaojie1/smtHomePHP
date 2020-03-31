<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShoppingBagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shopping_bags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('imgSrc');
            $table->string('goodsName');
            $table->integer('num');
            $table->integer('price');
            $table->string('goodsTitle');
            $table->string('goodsDetailMsg');
            $table->integer('provinceid');
            $table->integer('cityid');
            $table->integer('countyid');
            $table->string('deliveryTime');
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shopping_bags');
    }
}
