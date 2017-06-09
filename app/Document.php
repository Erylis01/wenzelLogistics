<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'name', 'type'
    ];

    public function loadings(){
        return $this->belongsToMany('App\Loading','document_loading');
    }
}
