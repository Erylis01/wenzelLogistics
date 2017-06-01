<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Palletstransfer extends Model
{
    protected $fillable = [
        'palletsAccount', 'palletsNumber', 'loading_referenz' , 'date', 'state', 'realPalletsNumber', 'documents', 'dateLastReminder', 'remindersNumber', 'reminderWarehouse'
    ];

    public function loading(){
        return $this->belongsTo('App\Palletstransfer', 'referenz','loading_referenz');
    }
}
