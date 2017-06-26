<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Palletsaccount extends Model
{
    protected $fillable = [
        'name','nickname', 'realNumberPallets', 'theoricalNumberPallets', 'type','adress', 'phone', 'email', 'namecontact'
    ];

    public function warehouses(){
        return $this->belongsToMany('App\Warehouse','palletsaccount_warehouse');
    }

    public function trucks(){
        return $this->hasMany('App\Truck','palletsaccount_name', 'name');
    }


}

