<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $fillable = [
        'name', 'adress', 'zipcode', 'town', 'country', 'phone', 'fax', 'email', 'namecontact',
    ];
}
