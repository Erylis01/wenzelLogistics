<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWarehousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('palletsaccount_name')->nullable();
            $table->string('name')->unique();
            $table->string('adress');
            $table->integer('zipcode')->unsigned();
            $table->string('town');
            $table->string('country');
            $table->string('phone', 15)->nullable();
            $table->string('fax', 15)->nullable();
            $table->string('email')->nullable();
            $table->string('namecontact')->nullable();

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
        Schema::dropIfExists('warehouses');
    }
}
