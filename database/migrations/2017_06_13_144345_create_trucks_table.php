<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrucksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('nickname');
//            $table->string('adress')->nullable();
            $table->string('licensePlate');
            $table->string('palletsaccount_name');
            $table->integer('realNumberPallets')->nullable();
            $table->integer('theoricalNumberPallets')->nullable();
            $table->timestamps();
            $table->foreign('palletsaccount_name')->references('name')->on('palletsaccount');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trucks');
    }
}
