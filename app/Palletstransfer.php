<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Palletstransfer extends Model
{
    protected $fillable = [
        'creditAccount', 'debitAccount', 'palletsNumber', 'type' , 'date', 'state', 'validate'
    ];

//    public function loading(){
//        return $this->belongsTo('App\Loading', 'loading_atrnr','atrnr');
//    }
//
//    public function palletsaccount(){
//        return $this->belongsTo('App\Palletsaccount', 'palletsaccount_name','name');
//    }

    public function documents(){
        return $this->belongsToMany('App\Document', 'document_palletstransfer');
    }
}
