<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loadings', function (Blueprint $table) {
            $table->integer('id')->unsigned()->index()->unique();
            $table->string('atrnr')->unique();
            $table->date('ladedatum');
            $table->date('entladedatum');
            $table->string('disp');
            $table->string('referenz');
            $table->string('auftraggeber');
            $table->string('beladestelle');
            $table->string('landb');
            $table->integer('plzb')->unsigned();
            $table->string('ortb');
            $table->string('entladestelle');
            $table->string('lande');
            $table->integer('plze')->unsigned();
            $table->string('orte');
            $table->string('anz');
            $table->string('art');
            $table->string('ware');
            $table->double('gewicht')->unsigned()->nullable();
            $table->double('vol')->unsigned()->nullable();
            $table->double('ldm')->unsigned()->nullable();
            $table->double('umsatz')->unsigned()->nullable();
            $table->double('aufwand')->unsigned()->nullable();
            $table->double('db')->nullable();
            $table->integer('trp')->nullable();
            $table->string('pt');
            $table->string('subfrachter');
            $table->string('kennzeichen')->nullable();
            $table->string('zusladestellen')->nullable();

            $table->string('state')->default('Untreated');
            $table->string('reasonUpdatePT')->nullable();
            $table->boolean('notExchange')->default(false);
//            $table->string('truckAccount')->nullable();

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
        Schema::dropIfExists('loadings');
    }
}
