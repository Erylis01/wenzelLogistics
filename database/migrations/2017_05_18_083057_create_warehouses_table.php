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
            $table->increments('id');
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
        });

//        Schema::table('loadings', function(Blueprint $table) {
//            $table->integer('Warehouse_id')->unsigned()->index();
//        });
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
