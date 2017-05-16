<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Loading extends Model
{
    protected $fillable = [
        'ladedatum', 'entladedatum', 'disp', 'atrnr', 'referenz', 'auftraggeber', 'beladestelle', 'landb', 'plzb', 'ortb', 'entladestelle', 'lande', 'plze', 'orte', 'anzahl', 'try1', 'try2', 'try3', 'ware', 'gewicht', 'umsatz', 'aufwand', 'db', 'trp', 'pt', 'subfrachter','pal', 'imklarung', 'paltausch', 'ruckgabewo', 'mahnung', 'blockierung', 'bearbeitungsdatum', 'palgebucht', 'state'
    ];
}
