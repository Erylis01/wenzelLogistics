<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name', 'adresse', 'palanzahl', 'idLoading', 'telefonnummer', 'kontakt'
        ];

    public function loadings(){
        return $this->hasMany(Loading::class);
    }
}
