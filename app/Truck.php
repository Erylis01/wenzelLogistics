<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    protected $fillable = [
        'name', 'adress','licensePlate', 'palletsaccount_name'
    ];

    public function palletsaccount(){
        return $this->belongsTo('App\Palletsaccount', 'palletsaccount_name','name');
    }
}
