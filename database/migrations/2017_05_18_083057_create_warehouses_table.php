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
            $table->string('name');
            $table->string('adresse')->nullable();
            $table->integer('palanzahl')->nullable();
            $table->string('telefonnummer', 15)->nullable();
            $table->string('kontakt')->nullable();
//            $table->integer('idLoading')->unsigned();
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
