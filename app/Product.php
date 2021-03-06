<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'goodsName',
        'price',
        'num',
        'imgSrc',
        'goodsTitle',
        'goodsDetailMsg',
        'deliveryTime',
        'type',
        'new',
    ];

}
