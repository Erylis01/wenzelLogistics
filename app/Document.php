<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name'
    ];

//    public function loadings(){
//        return $this->belongsToMany('App\Loading','document_loading');
//    }

    public function palletstransfers(){
        return $this->belongsToMany('App\Palletstransfer','document_palletstransfer');
    }
}
