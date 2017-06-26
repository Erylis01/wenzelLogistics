<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $fillable = [
        'name','licensePlate', 'palletsaccount_name', 'realNumberPallets', 'theoricalNumberPallets'
    ];

    public function palletsaccount(){
        return $this->belongsTo('App\Palletsaccount', 'palletsaccount_name','name');
    }
}
