<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use JWTAuth;
use App\Http\Controllers\ProductController;
//use App\Http\Controllers\ShoppingBagController;

class OrderController extends Controller
{
    //
    //
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    //订单列表
    public function orderList()
    {
        return $this->user
            ->order()
            ->get([
                'id',
                'orderNo',
                'goodsId',
                'goodsNumber',
                'totalPrice',
                'address',
                'provinceid',
                'cityid',
                'countyid',
                'phone',
                'name',
                'status',
            ])
            ->toArray();
    }

    //根据id订单详情页面
    public function detail(Request $request)
    {
        $Order = $this->user->order()->find($request->id);

        if (!$Order) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Order with id ' . $request->id . ' cannot be found'
            ], 400);
        }

        $goodsIdArray = explode(',', $Order->goodsId);
        $tempArray = [];

        foreach ($goodsIdArray as $value) {
            $getGoodsMsg = new ProductController();
            $tempObj = $getGoodsMsg->getGoodsMsg($value);
            if ($tempObj !== null) {
                array_push($tempArray, $tempObj);
            }
        }
        $Order->GoodsMsg = $tempArray;
        /*$created_at = $Order->created_at;
        $updated_at = $Order->updated_at;

        $Order->created_at = date( 'Y-m-d H:i:s', $created_at);
        $Order->updated_at = date( 'Y-m-d H:i:s', $updated_at);*/


        return $Order;

    }

    //新增订单
    public function newOrder(Request $request)
    {
        $Order = new Order();
        $Order->orderNo = $request->orderNo;
        $Order->goodsId = $request->goodsId;
        $Order->goodsNumber = $request->goodsNumber;
        $Order->totalPrice = $request->totalPrice;
        $Order->provinceid = $request->provinceid;
        $Order->cityid = $request->cityid;
        $Order->countyid = $request->countyid;
        $Order->address = $request->address;
        $Order->phone = $request->phone;
        $Order->name = $request->name;
        $Order->status = $request->status;


        if ($this->user->order()->save($Order)) {
            /*$ShoppingBagController = new ShoppingBagController;


            $goodsIdArray = explode(',', $Order->goodsId);
            foreach ($goodsIdArray as $value){
                $ShoppingBagController = new ShoppingBagController();
                $ShoppingBagController->billSuccess($value);
            }*/

            return response()->json([
                'success' => true,
                'Order' => $Order
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Order could not be added'
            ], 500);
        }


    }


    //删除订单
    public function deleteOrder(Request $request)
    {
        $Order = $this->user->order()->find($request->id);
        if (!$Order) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Order with id ' . $request->id . ' cannot be found'
            ], 400);
        }

        if ($Order->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Order could not be deleted'
            ], 500);
        }

    }

    //修改订单
    public function editOrder(Request $request)
    {
        $Order = $this->user->order()->find($request->id);

        if (!$Order) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Order with id ' . $request->id . ' cannot be found'
            ], 400);
        }

        $updated = $Order->fill($request->all())
            ->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Order could not be updated'
            ], 500);
        }
    }
}
