<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePalletstransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('palletstransfers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('palletsNumber');
            $table->string('palletsAccount');
            $table->string('loading_referenz');
            $table->date('date');
            $table->integer('realPalletsNumber')->nullable();
            $table->boolean('documents')->default(false);
            $table->boolean('state')->default(false);
            $table->date('dateLastReminder')->nullable();
            $table->integer('remindersNumber')->nullable();
            $table->string('reminderWarehouse')->nullable();
            $table->timestamps();

            $table->foreign('loading_referenz')->references('referenz')->on('loadings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('palletstransfers');
    }
}
