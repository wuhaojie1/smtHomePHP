<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');
Route::post('products/list', 'ProductController@index');
Route::post('products/detail', 'ProductController@show');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'ApiController@logout');
    Route::post('user', 'ApiController@getAuthUser');
    Route::post('edit', 'ApiController@userMsgUpdate');

    //products
//    Route::post('products/list', 'ProductController@index');
    Route::post('products/save', 'ProductController@store');
//    Route::post('products/detail', 'ProductController@show');
    Route::post('products/update', 'ProductController@update');
    Route::delete('products/delete', 'ProductController@destroy');

    //shippingAdress
    Route::get('shippingAddress/list', 'ShippingaddressController@index');
    Route::post('shippingAddress/detail', 'ShippingaddressController@show');
    Route::post('shippingAddress/new', 'ShippingaddressController@store');
    Route::post('shippingAddress/update', 'ShippingaddressController@update');
    Route::post('shippingAddress/delete', 'ShippingaddressController@destroy');

    //area
    Route::get('area', 'AreaController@areaList');

    //shoppingBag
    Route::post('shopping/list', 'ShoppingBagController@find');//查{传id为条件查询不传id为查询全部}
    Route::post('shopping/save', 'ShoppingBagController@store');//增
    Route::post('shopping/delete', 'ShoppingBagController@destroy');//删
    Route::post('shopping/update', 'ShoppingBagController@update');//改

});

