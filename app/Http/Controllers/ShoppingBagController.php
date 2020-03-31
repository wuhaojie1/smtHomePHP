<?php

namespace App\Http\Controllers;

use App\ShoppingBag;
use Illuminate\Http\Request;
use JWTAuth;

class ShoppingBagController extends Controller
{
    //
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    //查找购物车列表
    public function find(Request $request)
    {
        if ($request->id) {
            $shopping = $this->user->shoppingBag()->find($request->id);
            if (!$shopping) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, product with id ' . $request->id . ' cannot be found'
                ], 400);
            }

            return $shopping;
        } else {
            return $this->user
                ->shoppingBag()
                ->get([
                    'id',
                    'imgSrc',
                    'goodsName',
                    'num',
                    'price',
                    'goodsTitle',
                    'goodsDetailMsg',
                    'deliveryTime',
                ])
                ->toArray();
        }
    }

    //新增
    public function store(Request $request)
    {
        $this->validate($request, [
            /*'name' => 'required',
            'provinceid' => 'required|integer',
            'cityid' => 'required|integer',
            'address' => 'required',
            'phone' => 'required'*/
        ]);
        $ShoppingBag = new ShoppingBag();
        $ShoppingBag->imgSrc = $request->imgSrc;
        $ShoppingBag->goodsName = $request->goodsName;
        $ShoppingBag->num = $request->num;
        $ShoppingBag->price = $request->price;
        $ShoppingBag->goodsTitle = $request->goodsTitle;
        $ShoppingBag->goodsDetailMsg = $request->goodsDetailMsg;
        $ShoppingBag->provinceid = $request->provinceid;
        $ShoppingBag->cityid = $request->cityid;
        $ShoppingBag->countyid = $request->countyid;
        $ShoppingBag->deliveryTime = $request->deliveryTime;

        if ($this->user->shoppingBag()->save($ShoppingBag))
            return response()->json([
                'success' => true,
                'ShoppingBag' => $ShoppingBag
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, ShoppingBag could not be added'
            ], 500);

    }

    //删除
    public function destroy(Request $request)
    {
        $ShoppingBag = $this->user->shoppingBag()->find($request->id);

        if (!$ShoppingBag) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, shippingadress with id ' . $request->id . ' cannot be found'
            ], 400);
        }

        if ($ShoppingBag->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'ShoppingBag could not be deleted'
            ], 500);
        }
    }

    //修改
    public function update(Request $request)
    {
        $ShoppingBag = $this->user->shoppingBag()->find($request->id);

        if (!$ShoppingBag) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, ShoppingBag with id ' . $request->id . ' cannot be found'
            ], 400);
        }

        $updated = $ShoppingBag->fill($request->all())
            ->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, ShoppingBag could not be updated'
            ], 500);
        }
    }

}
