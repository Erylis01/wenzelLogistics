<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loading extends Model
{
    protected $fillable = [
        'id','ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle', 'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anz', 'art', 'ware', 'gewicht','vol', 'ldm', 'umsatz', 'aufwand', 'db', 'trp', 'pt', 'subfrachter','kennzeichen', 'zusladestellen', 'ruckgabewo', 'mahnung', 'blockierung', 'bearbeitungsdatum', 'palgebucht',
        'state','reasonUpdatePT', 'notExchange'
    ];

    public function palletstransfers(){
        return $this->hasMany('App\Palletstransfer','loading_atrnr', 'atrnr');
    }

//    public function documents(){
//        return $this->belongsToMany('App\Document', 'document_loading');
//    }
}
