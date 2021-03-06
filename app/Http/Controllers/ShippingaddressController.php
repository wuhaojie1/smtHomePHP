<?php

namespace App\Http\Controllers;

use App\shippingaddress;
use Illuminate\Http\Request;
use JWTAuth;

class ShippingaddressController extends Controller
{
    //
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    //查询
    public function index()
    {
        return $this->user
            ->shippingadress()
            ->get([
                'id',
                'isdefault',
                'provinceid',
                'cityid',
                'countyid',
                'address',
                'name',
                'phone'
            ])
            ->toArray();
    }

    //根据id查询

    public function show(Request $request)
    {
        $shippingaddress = $this->user->shippingadress()->find($request->id);

        if (!$shippingaddress) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product with id ' . $request->id . ' cannot be found'
            ], 400);
        }

        return $shippingaddress;
    }

    //新增
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'provinceid' => 'required|integer',
            'cityid' => 'required|integer',
            'address' => 'required',
            'phone' => 'required'
        ]);

        $shippingaddress = new shippingaddress();
        $shippingaddress->name = $request->name;
        $shippingaddress->isdefault = $request->isdefault;
        $shippingaddress->provinceid = $request->provinceid;
        $shippingaddress->cityid = $request->cityid;
        $shippingaddress->countyid = $request->countyid;
        $shippingaddress->address = $request->address;
        $shippingaddress->phone = $request->phone;

        if ($this->user->shippingadress()->save($shippingaddress))
            return response()->json([
                'success' => true,
                'shippingaddress' => $shippingaddress
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, shippingaddress could not be added'
            ], 500);
    }
    //删除
    public function destroy(Request $request)
    {
        $shippingadress = $this->user->shippingadress()->find($request->id);

        if (!$shippingadress) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, shippingadress with id ' . $request->id . ' cannot be found'
            ], 400);
        }

        if ($shippingadress->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'shippingaddress could not be deleted'
            ], 500);
        }
    }

    //修改
    public function update(Request $request)
    {
        $shippingadress = $this->user->shippingadress()->find($request->id);

        if (!$shippingadress) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, shippingadress with id ' . $request->id . ' cannot be found'
            ], 400);
        }

        $updated = $shippingadress->fill($request->all())
            ->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, shippingadress could not be updated'
            ], 500);
        }
    }


}
