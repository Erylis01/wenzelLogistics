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
            $table->integer('atrnr')->unsigned()->unique();
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
            $table->double('gewicht')->unsigned();
            $table->integer('vol')->unsigned()->nullable();
            $table->double('ldm')->unsigned();
            $table->double('umsatz')->unsigned();
            $table->double('aufwand')->unsigned();
            $table->double('db');
            $table->integer('trp');
            $table->string('pt');
            $table->string('subfrachter');
            $table->string('kennzeichen')->nullable();
            $table->string('zusladestellen')->nullable();


            $table->string('ruckgabewo')->nullable();
            $table->string('mahnung')->nullable();
            $table->string('blockierung')->nullable();
            $table->date('bearbeitungsdatum')->nullable();
            $table->string('palgebucht')->nullable();

            $table->string('state')->default('not OK');
            $table->string('reasonUpdatePT')->nullable();

            $table->integer('warehouse_id')->nullable();
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
