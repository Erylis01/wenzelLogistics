<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loading extends Model
{
    protected $fillable = [
        'ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle', 'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anz', 'art', 'ware', 'gewicht','vol', 'ldm', 'umsatz', 'aufwand', 'db', 'trp', 'pt', 'subfrachter','kennzeichen', 'zusladestellen', 'ruckgabewo', 'mahnung', 'blockierung', 'bearbeitungsdatum', 'palgebucht',
        'state','reasonUpdatePT','numberLoadingPlace', 'numberOfflodaingPlace',
        'numberPalletsLoadingPlace1', 'accountCreditLoadingPlace1','accountDebitLoadingPlace1','stateLoadingPlace1', 'validateLoadingPlace1',
        'numberPalletsOffloadingPlace1', 'accountCreditOffloadingPlace1','accountDebitOffloadingPlace1','stateOffloadingPlace1', 'validateOffloadingPlace1',
    'numberPalletsLoadingPlace2', 'accountCreditLoadingPlace2','accountDebitLoadingPlace2','stateLoadingPlace2', 'validateLoadingPlace2',
        'numberPalletsOffloadingPlace2', 'accountCreditOffloadingPlace2','accountDebitOffloadingPlace2','stateOffloadingPlace2', 'validateOffloadingPlace2',
    'numberPalletsLoadingPlace3', 'accountCreditLoadingPlace3','accountDebitLoadingPlace3','stateLoadingPlace3', 'validateLoadingPlace3',
        'numberPalletsOffloadingPlace3', 'accountCreditOffloadingPlace3','accountDebitOffloadingPlace3','stateOffloadingPlace3', 'validateOffloadingPlace3',
    'numberPalletsLoadingPlace4', 'accountCreditLoadingPlace4','accountDebitLoadingPlace4','stateLoadingPlace4', 'validateLoadingPlace4',
        'numberPalletsOffloadingPlace4', 'accountCreditOffloadingPlace4','accountDebitOffloadingPlace4','stateOffloadingPlace4', 'validateOffloadingPlace4',
    'numberPalletsLoadingPlace5', 'accountCreditLoadingPlace5','accountDebitLoadingPlace5','stateLoadingPlace5', 'validateLoadingPlace5',
        'numberPalletsOffloadingPlace5', 'accountCreditOffloadingPlace5','accountDebitOffloadingPlace5','stateOffloadingPlace5', 'validateOffloadingPlace5'
    ];

    public function palletstransfers(){
        return $this->hasMany('App\Palletstransfer','loading_atrnr', 'atrnr');
    }

    public function documents(){
        return $this->belongsToMany('App\Document', 'document_loading');
    }

//    public function palletsaccounts(){
//        return $this->belongsToMany('App\Palletsaccount', 'loading_palletsaccount');
//    }
}
