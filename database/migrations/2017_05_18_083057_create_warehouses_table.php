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
            $table->increments('id')->unique()->index();
            $table->string('name')->unique();
            $table->string('nickname')->unique()->nullable();
            $table->string('adress')->nullable();
            $table->string('zipcode', 10);
            $table->string('town');
            $table->string('country');
            $table->string('phone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('email')->nullable();
            $table->string('namecontact')->nullable();

            $table->timestamps();

        });

        Schema::create('palletsaccount_warehouse',function (Blueprint $table) {
$table->increments('id');
$table->integer('palletsaccount_id')->unsigned()->index();
            $table->integer('warehouse_id')->unsigned()->index();
            $table->foreign('palletsaccount_id')->references('id')->on('palletsaccounts')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
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
