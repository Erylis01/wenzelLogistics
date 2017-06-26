<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalletsaccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('palletsaccounts', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('name')->unique();
            $table->string('nickname')->unique()->nullable();
//            $table->integer('realNumberPallets')->nullable();
//            $table->integer('theoricalNumberPallets')->nullable();
            $table->string('type');
            $table->string('adress')->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('email')->nullable();
            $table->string('namecontact')->nullable();

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
        Schema::dropIfExists('palletsaccounts');
    }
}
