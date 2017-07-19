<?php

use Carbon\Carbon;
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
            $table->string('loading_atrnr')->nullable();
            $table->string('creditAccount')->nullable();
            $table->string('debitAccount')->nullable();
            $table->integer('palletsNumber')->nullable();
            $table->date('date')->default(Carbon::now()->format('Y-m-d'));
            $table->string('state')->default('Waiting documents');
            $table->boolean('validate')->default(false);
            $table->string('type');
            $table->integer('normalTransferAssociated')->nullable();
            $table->string('details')->nullable();
            $table->boolean('notExchange')->nullable();
//            $table->date('dateLastReminder')->nullable();
//            $table->integer('remindersNumber')->nullable();
//            $table->string('reminderWarehouse')->nullable();
            $table->timestamps();

            $table->foreign('loading_atrnr')->references('atrnr')->on('loadings');

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
