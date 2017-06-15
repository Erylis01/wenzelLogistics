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
            $table->double('vol')->unsigned()->nullable();
            $table->double('ldm')->unsigned();
            $table->double('umsatz')->unsigned();
            $table->double('aufwand')->unsigned();
            $table->double('db');
            $table->integer('trp');
            $table->string('pt');
            $table->string('subfrachter');
            $table->string('kennzeichen')->nullable();
            $table->string('zusladestellen')->nullable();

            $table->string('state')->default('Untreated');
            $table->string('reasonUpdatePT')->nullable();
$table->integer('numberLoadingPlace')->default(1);
            $table->integer('numberOffloadingPlace')->default(1);

            $table->integer('numberPalletsLoadingPlace1')->nullable();
            $table->string('accountCreditLoadingPlace1')->nullable();
            $table->string('accountDebitLoadingPlace1')->nullable();
            $table->string('stateLoadingPlace1')->default('Untreated');
            $table->boolean('validateLoadingPlace1')->default(false);
            $table->integer('countTimeLoadingPlace1')->default(0);

            $table->integer('numberPalletsOffloadingPlace1')->nullable();
            $table->string('accountCreditOffloadingPlace1')->nullable();
            $table->string('accountDebitOffloadingPlace1')->nullable();
            $table->string('stateOffloadingPlace1')->default('Untreated');
            $table->boolean('validateOffloadingPlace1')->default(false);
            $table->integer('countTimeOffloadingPlace1')->default(0);

            $table->integer('numberPalletsLoadingPlace2')->nullable();
            $table->string('accountCreditLoadingPlace2')->nullable();
            $table->string('accountDebitLoadingPlace2')->nullable();
            $table->string('stateLoadingPlace2')->default('Untreated');
            $table->boolean('validateLoadingPlace2')->default(false);
            $table->integer('countTimeLoadingPlace2')->default(0);

            $table->integer('numberPalletsOffloadingPlace2')->nullable();
            $table->string('accountCreditOffloadingPlace2')->nullable();
            $table->string('accountDebitOffloadingPlace2')->nullable();
            $table->string('stateOffloadingPlace2')->default('Untreated');
            $table->boolean('validateOffloadingPlace2')->default(false);
            $table->integer('countTimeOffloadingPlace2')->default(0);

            $table->integer('numberPalletsLoadingPlace3')->nullable();
            $table->string('accountCreditLoadingPlace3')->nullable();
            $table->string('accountDebitLoadingPlace3')->nullable();
            $table->string('stateLoadingPlace3')->default('Untreated');
            $table->boolean('validateLoadingPlace3')->default(false);
            $table->integer('countTimeLoadingPlace3')->default(0);

            $table->integer('numberPalletsOffloadingPlace3')->nullable();
            $table->string('accountCreditOffloadingPlace3')->nullable();
            $table->string('accountDebitOffloadingPlace3')->nullable();
            $table->string('stateOffloadingPlace3')->default('Untreated');
            $table->boolean('validateOffloadingPlace3')->default(false);
            $table->integer('countTimeOffloadingPlace3')->default(0);

            $table->integer('numberPalletsLoadingPlace4')->nullable();
            $table->string('accountCreditLoadingPlace4')->nullable();
            $table->string('accountDebitLoadingPlace4')->nullable();
            $table->string('stateLoadingPlace4')->default('Untreated');
            $table->boolean('validateLoadingPlace4')->default(false);
            $table->integer('countTimeLoadingPlace4')->default(0);

            $table->integer('numberPalletsOffloadingPlace4')->nullable();
            $table->string('accountCreditOffloadingPlace4')->nullable();
            $table->string('accountDebitOffloadingPlace4')->nullable();
            $table->string('stateOffloadingPlace4')->default('Untreated');
            $table->boolean('validateOffloadingPlace4')->default(false);
            $table->integer('countTimeOffloadingPlace4')->default(0);

            $table->integer('numberPalletsLoadingPlace5')->nullable();
            $table->string('accountCreditLoadingPlace5')->nullable();
            $table->string('accountDebitLoadingPlace5')->nullable();
            $table->string('stateLoadingPlace5')->default('Untreated');
            $table->boolean('validateLoadingPlace5')->default(false);
            $table->integer('countTimeLoadingPlace5')->default(0);

            $table->integer('numberPalletsOffloadingPlace5')->nullable();
            $table->string('accountCreditOffloadingPlace5')->nullable();
            $table->string('accountDebitOffloadingPlace5')->nullable();
            $table->string('stateOffloadingPlace5')->default('Untreated');
            $table->boolean('validateOffloadingPlace5')->default(false);
            $table->integer('countTimeOffloadingPlace5')->default(0);

            $table->string('accountTruck')->nullable();
            $table->string('stateTruck')->default('Untreated');
            $table->boolean('validateTruck')->default(false);
            $table->boolean('firstTimeTruck')->default(true);


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
