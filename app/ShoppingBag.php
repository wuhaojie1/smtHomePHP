<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShoppingBag extends Model
{
    //
    protected $fillable = [
        'imgSrc',
        'goodsName',
        'num',
        'price',
        'goodsTitle',
        'goodsDetailMsg',
        'provinceid',
        'cityid',
        'countyid',
        'deliveryTime',
    ];
}
