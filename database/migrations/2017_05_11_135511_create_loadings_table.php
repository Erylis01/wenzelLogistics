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
            $table->integer('atrNr');
            $table->string('referenz');
            $table->string('auftraggeber');
            $table->string('beladestelle');
            $table->string('landB');
            $table->integer('plzB');
            $table->string('ortB');
            $table->string('entladestelle');
            $table->string('landE');
            $table->integer('plzE');
            $table->string('ortE');
            $table->string('anzahl');
            $table->string('TRY1');
            $table->string('TRY2');
            $table->string('TRY3');
            $table->integer('ware');
            $table->double('gewicht');
            $table->double('umsatz');
            $table->double('aufwand');
            $table->double('db');
            $table->integer('trp');
            $table->string('pt');
            $table->string('subfrächter');
            $table->string('pal');
            $table->string('imKlärung');
            $table->string('palTauschVereinbart');
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
