<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
//        Schema::create('document_loading',function (Blueprint $table) {
//            $table->increments('id');
//            $table->integer('document_id')->unsigned();
//            $table->integer('loading_id')->unsigned();
//            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
//            $table->foreign('loading_id')->references('id')->on('loadings')->onDelete('cascade');
//        });

        Schema::create('document_palletstransfer',function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id')->unsigned();
            $table->integer('palletstransfer_id')->unsigned();
            $table->foreign('document_id')->references('id')->on('documents')->onDelete('cascade');
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
        Schema::dropIfExists('documents');
    }
}
