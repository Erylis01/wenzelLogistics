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
            $table->increments('id');
            $table->date('ladedatum');
            $table->date('entladedatum');
            $table->string('disp');
            $table->integer('atrnr');
            $table->string('referenz');
            $table->string('auftraggeber');
            $table->string('beladestelle');
            $table->string('landb');
            $table->integer('plzb');
            $table->string('ortb');
            $table->string('entladestelle');
            $table->string('lande');
            $table->integer('plze');
            $table->string('orte');
            $table->string('anzahl');
            $table->string('try1');
            $table->string('try2');
            $table->string('try3');
            $table->string('ware');
            $table->double('gewicht');
            $table->double('umsatz');
            $table->double('aufwand');
            $table->double('db');
            $table->integer('trp');
            $table->string('pt');
            $table->string('subfrachter');
            $table->string('pal')->nullable();
            $table->string('imklarung')->nullable();
            $table->string('paltauschvereinbart')->nullable();
            $table->string('ruckgabewo')->nullable();
            $table->string('mahnung')->nullable();
            $table->string('blockierung')->nullable();
            $table->date('bearbeitungsdatum')->nullable();
            $table->string('palgebucht')->nullable();

            $table->string('state')->default('not OK');
            $table->string('reasonUpdatePalTauschVereinbart')->nullable();
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
