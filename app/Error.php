<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
    protected $fillable = [
        'name', 'description'
    ];
    public function palletstransfers(){
        return $this->belongsToMany('App\Palletstransfer','error_palletstransfer');
    }
}
