<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterAuthRequest;
use App\User;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public $loginAfterSignUp = true;


    public function register(RegisterAuthRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            'success' => true,
            'data' => $user
        ], 200);
    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;

        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }

    public function logout(Request $request)
    {
        /*$this->validate($request->header(''), [
            'token' => 'required'
        ]);*/

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], 500);
        }
    }

    public function getAuthUser(Request $request)
    {
        //        $this->validate($request, [
        //            'token' => 'required'
        //        ]);
        //
        $user = JWTAuth::authenticate($request->token);

        return response()->json([
            'user' => $user
        ]);
    }

    public function userMsgUpdate(Request $request)
    {
        $db = DB::table('users');

        $db->where('id', '=', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'headimg' => $request->headimg,
            'country' => $request->country

        ]);

//        $user = $this->users()->find($request->id);
//
//        $updated = $user->fill($request->all())->save();
//
        return response()->json([
            'success' => true
        ]);
        /*if ($updated) {
            return response()->json([
                'success' => $request
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, users could not be updated'
            ], 500);
        }*/
    }

    public function getUserInfo(Request $request)
    {
        return response()->json([
            'success' => true,
            'userInfo' => $request
        ]);
    }

    public function productsList (Request $request){
        $products = DB::table('products');
        if($request->type){
            $tempData = $products->where('type', $request->type)
                ->orderBy('id', 'desc')
                ->get();
            if (!$tempData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sorry, product with type ' . $request->type . ' cannot be found'
                ], 400);
            }
            return $tempData;
        } else{
            return $products->get([
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


    public function productsDetail(Request $request){
        $products = DB::table('products');

        $tempData = $products->find($request->id);
        if (!$tempData) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, product with id ' . $request->id . ' cannot be found'
            ], 400);
        }
        return response()->json([
            'success' => true,
            'data' => $tempData
        ]);
    }
}
