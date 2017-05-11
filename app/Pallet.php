<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pallet extends Model
{
    protected $fillable = [
        'ladedatum', 'entladedatum', 'disp', 'atrNr', 'referenz', 'auftraggeber', 'beladestelle', 'landB', 'plzB', 'ortB', 'entladestelle', 'landE', 'plzE', 'ortE', 'anzahl', 'TRY1', 'TRY2', 'TRY3', 'ware', 'gewicht', 'umsatz', 'aufwand', 'db', 'trp', 'pt', 'subfrächter',
    ];
}
