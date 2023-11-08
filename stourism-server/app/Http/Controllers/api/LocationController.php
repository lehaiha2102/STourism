<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LocationController extends Controller
{
    public function getProvinceList(){
        $data = DB::table('province')
            ->join('district', 'province.id', '=', 'district.province_id')
            ->join('ward', 'district.id', '=', 'ward.district_id')
            ->join('products', 'ward.id', '=', 'products.ward_id')
            ->distinct() 
            ->select('province.*')
            ->get();
        return response()->json(['data' => $data]);
    }
}
