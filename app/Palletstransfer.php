<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Palletstransfer extends Model
{
    protected $fillable = [
        'palletsaccount_name', 'palletsNumber', 'loading_atrnr' , 'date', 'state', 'realPalletsNumber', 'documents', 'dateLastReminder', 'remindersNumber', 'reminderWarehouse'
    ];

    public function loading(){
        return $this->belongsTo('App\Loading', 'loading_atrnr','atrnr');
    }

    public function palletsaccount(){
        return $this->belongsTo('App\Palletsaccount', 'palletsaccount_name','name');
    }
}
