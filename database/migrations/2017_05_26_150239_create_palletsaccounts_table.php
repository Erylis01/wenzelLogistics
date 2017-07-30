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
            $table->integer('realNumberPallets')->nullable();
            $table->integer('theoricalNumberPallets')->nullable();
            $table->integer('theoricalPalletsDebt')->nullable();
            $table->integer('realPalletsDebt')->nullable();
            $table->string('type');
            $table->string('adress')->nullable();
            $table->string('zipcode', 20)->nullable();
            $table->string('town')->nullable();
            $table->string('country')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('details')->nullable();
            $table->boolean('notExchange')->default(false);

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
