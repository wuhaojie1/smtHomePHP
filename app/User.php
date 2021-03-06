<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    //关联产品
    public function products()
    {
        return $this->hasMany(Product::class);
    }
    //关联用户收货地址
    public function shippingadress()
    {
        return $this->hasMany(shippingaddress::class);
    }
    //关联用户购物车
    public function shoppingBag()
    {
        return $this->hasMany(shoppingBag::class );
    }

    //关联订单
    public function order()
    {
        return $this->hasMany(order::class );
    }

}
