<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Palletstransfer extends Model
{
    protected $fillable = [
        'palletsAccount', 'palletsNumber', 'loading_atrnr' , 'date', 'state', 'realPalletsNumber', 'documents', 'dateLastReminder', 'remindersNumber', 'reminderWarehouse'
    ];

    public function loading(){
//        return $this->belongsTo('App\Palletstransfer', 'atrnr','loading_atrnr');
        return $this->belongsTo('App\Loading', 'atrnr','loading_atrnr');
    }
}
