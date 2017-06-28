<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('errors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::create('error_palletstransfer',function (Blueprint $table) {
            $table->increments('id');
            $table->integer('error_id')->unsigned();
            $table->integer('palletstransfer_id')->unsigned();
            $table->foreign('error_id')->references('id')->on('errors')->onDelete('cascade');
            $table->foreign('palletstransfer_id')->references('id')->on('palletstransfers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('errors');
    }
}
