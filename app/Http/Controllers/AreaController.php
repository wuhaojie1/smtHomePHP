<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cblink\Region\Region;
use JWTAuth;

class areaController extends Controller
{
    //
    protected $user;

    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    public function areaList()
    {
        $region = new Region();
        //查找出全部的省份
        $region->province = $region->allProvinces(); // 全部省份


        $array = [];
        //遍历省份
        foreach ($region->province as $value){
            $children= $this->getChildren($value->id);
            array_push($array, $children[0]);
        }
//        $region->cities = $region->allCities(); // 全部城市
//        $region->areas = $region->allAreas(); // 全部区

//        $region->nest($id = null); // 展示全部子区域，可指定某个省或市id
//        $region->nestFromChild($id); // 根据市或区id展示其所有父结构


        return response()->json([
            'success' => true,
            'area' => $array
        ]);

    }

    public function getChildren($childrenId)
    {
        $region = new Region();
        return $region->nest($id = $childrenId);
    }
}
