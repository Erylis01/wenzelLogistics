<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Palletstransfer extends Model
{
    protected $fillable = [
        'palletsAccount', 'palletsNumber', 'loadingRef' , 'date', 'state'
    ];
}
