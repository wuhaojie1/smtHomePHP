<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class shippingaddress extends Model
{
    //
    protected $fillable = [
        'isdefault',
        'provinceid',
        'cityid',
        'countyid',
        'address',
        'name',
        'contactphone'
    ];

}
