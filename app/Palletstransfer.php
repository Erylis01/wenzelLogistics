<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Palletstransfer extends Model
{
    protected $fillable = [
        'creditAccount', 'debitAccount', 'palletsNumber', 'type' , 'date', 'state', 'validate', 'loading_atrnr',  'details','normalTransferAssociated'
    ];

    public function loading(){
        return $this->belongsTo('App\Loading', 'loading_atrnr','atrnr');
    }

    public function documents(){
        return $this->belongsToMany('App\Document', 'document_palletstransfer');
    }
    public function errors(){
        return $this->belongsToMany('App\Error', 'error_palletstransfer');
    }
}
