<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use JWTAuth;

class ProductController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function index(Request $request)
    {
        if ($request->type) {
            $products = $this->user->products()->where('type', $request->type)
                ->orderBy('id', 'desc')
                ->get();
            if (!$products) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, product with type ' . $request->type . ' cannot be found'
                ], 400);
            }

            return $products;

        } else {
            return $this->user
                ->products()
                ->get([
                    'id',
                    'goodsName',
                    'price',
                    'num',
                    'imgSrc',
                    'goodsTitle',
                    'goodsDetailMsg',
                    'deliveryTime',
                    'type',
                    'category'
                ])
                ->toArray();
        }

    }

    public function show(Request $request)
    {
        $product = $this->user->products()->find($request->id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product with id ' . $request->id . ' cannot be found'
            ], 400);
        }

        return $product;
    }

    public function getGoodsMsg($id)
    {
        $product = $this->user->products()->find($id);
//        if (!$product) {
//            return response()->json([
//                'success' => false,
//                'message' => 'Sorry, product with id ' . $id . ' cannot be found'
//            ], 400);
//        }

        return $product;
    }


    //新增
    public function store(Request $request)
    {
        /*$this->validate($request, [
            'name' => 'required',
            'price' => 'required|integer',
            'quantity' => 'required|integer'
        ]);*/

        $product = new Product();
        $product->goodsName = $request->goodsName;
        $product->price = $request->price;
        $product->num = $request->num;
        $product->imgSrc = $request->imgSrc;
        $product->goodsTitle = $request->goodsTitle;
        $product->goodsDetailMsg = $request->goodsDetailMsg;
        $product->deliveryTime = $request->deliveryTime;
        $product->type = $request->type;
        $product->new = $request->new;
        $product->category = $request->category;

        if ($this->user->products()->save($product))
            return response()->json([
                'success' => true,
                'product' => $product
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product could not be added'
            ], 500);
    }

    //修改
    public function update(Request $request)
    {
        $product = $this->user->products()->find($request->id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product with id ' . $request->id . ' cannot be found'
            ], 400);
        }

        $updated = $product->fill($request->all())
            ->save();

        if ($updated) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product could not be updated'
            ], 500);
        }
    }

    //删除
    public function destroy(Request $request)
    {
        $product = $this->user->products()->find($request->id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product with id ' . $request->id . ' cannot be found'
            ], 400);
        }

        if ($product->delete()) {
            return response()->json([
                'success' => true
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product could not be deleted'
            ], 500);
        }
    }
}
