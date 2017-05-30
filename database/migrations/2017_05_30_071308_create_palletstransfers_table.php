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
            $table->string('loadingRef');
            $table->date('date');
            $table->integer('realPalletsNumber')->nullable();
            $table->boolean('documents')->default(false);
            $table->boolean('state')->default(false);
            $table->date('dateLastReminder')->nullable();
            $table->integer('remindersNumber')->nullable();
            $table->string('reminderEmail')->nullable();
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
        Schema::dropIfExists('palletstransfers');
    }
}
