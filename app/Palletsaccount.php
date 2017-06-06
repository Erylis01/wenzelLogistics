<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Palletsaccount extends Model
{
    protected $fillable = [
        'name', 'realNumberPallets', 'theoricalNumberPallets'
    ];

    public function warehouses(){
        return $this->belongsToMany('App\Warehouse','palletsaccount_warehouse');
    }

    public function carrier(){
        return $this->hasOne('App\Carrier','palletsaccount_name', 'name');
    }

    public function palletstransfers(){
        return $this->hasMany('App\Palletstransfer','palletsaccount_name', 'name');
    }
}

