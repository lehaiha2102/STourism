<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProductController extends Controller
{
    public function getHotelProductList(){
        $data = DB::table('products')
            ->join('categories_product', 'categories_product.product_id', '=', 'products.id')
                ->join('categories', 'categories.id', '=', 'categories_product.category_id')
                ->select('products.*')
                ->distinct()
                ->paginate(9);
        return response()->json(['data' => $data]);
    }

    public function getProductList(){
        $data = DB::table('products')->get();
        return response()->json(['data' => $data]);
    }

    public function getProduct($slug){
        $data = DB::table('products')
        ->join('business', 'products.business_id', '=', 'business.id')
        ->where('product_slug', $slug)->first();
        return response()->json(['data' => $data]);
    }

    public function searchRoom(Request $request){
        $province = $request->province;
        $adult = $request->adult;
        $child = $request->child;
        $room_quantity = $request->roomQuantity;

        $data = DB::table('rooms')
            ->join('products', 'products.id', '=', 'rooms.product_id')
            ->join('categories_product', 'categories_product.product_id', '=', 'products.id')
            ->join('categories', 'categories.id', '=', 'categories_product.category_id')
            ->join('ward', 'ward.id', '=', 'products.ward_id')
            ->join('district', 'district.id', '=', 'ward.district_id')
            ->join('province', 'province.id', '=', 'district.province_id')
            ->select('rooms.*', 'products.*')
            ->where('province.id', '=', $province)
            ->where('rooms.room_quantity', '>=', $room_quantity)
            ->where('rooms.adult_capacity', '>=', $adult)
            ->where('rooms.children_capacity', '>=', $child)
            ->distinct()
            ->get();

        if($data->isEmpty()){
            return response()->json([
                'messenger' => 'Không tìm thấy dịch vụ cần tìm'
            ]);
        }

        return response()->json([
            'data' => $data
        ]);
    }

}
