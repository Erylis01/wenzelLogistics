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
            $table->increments('id')->unique();
            $table->integer('loading_atrnr')->unsigned()->index();
            $table->string('palletsaccount_name');
            $table->integer('palletsNumber');
//            $table->string('palletsaccount_name');
            $table->date('date');
            $table->integer('realPalletsNumber')->nullable();
            $table->boolean('documents')->default(false);
            $table->boolean('state')->default(false);
            $table->date('dateLastReminder')->nullable();
            $table->integer('remindersNumber')->nullable();
            $table->string('reminderWarehouse')->nullable();
            $table->timestamps();

            $table->foreign('loading_atrnr')->references('atrnr')->on('loadings');
            $table->foreign('palletsaccount_name')->references('name')->on('palletsaccounts');
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
