<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Palletsaccount extends Model
{
    protected $fillable = [
        'name', 'realNumberPallets', 'theoricalNumberPallets'
    ];

    public function warehouses(){
        return $this->hasMany('App\Warehouse','palletsaccount_name', 'name');
    }
}
