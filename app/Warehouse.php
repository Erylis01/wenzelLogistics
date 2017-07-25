<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name','nickname', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact'
    ];

    public function palletsaccounts(){
        return $this->belongsToMany('App\Palletsaccount', 'palletsaccount_warehouse');
    }
}
